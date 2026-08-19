<?php

namespace App\Services\ContaPyme;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ContaPymeClient
{
    private ?string $controlKey = null;

    /**
     * Authenticate and return the session metadata supplied by ContaPyme.
     */
    public function authenticate(): array
    {
        $result = $this->send(
            service: 'TBasicoGeneral',
            method: 'GetAuth',
            data: [
                'email' => $this->requiredConfig('email'),
                'password' => $this->passwordHash(),
                'idmaquina' => (string) config('contapyme.machine_id', '1'),
            ],
            controlKey: '',
        );

        $data = data_get($result, 'respuesta.datos');

        if (! is_array($data) || blank($data['keyagente'] ?? null)) {
            throw new ContaPymeException('GetAuth no devolvió keyagente.');
        }

        $this->controlKey = (string) $data['keyagente'];

        return $data;
    }

    /**
     * Return products and their balances in every warehouse.
     */
    public function inventoryBalances(): array
    {
        $result = $this->call(
            service: 'TCatElemInv',
            method: 'GetSaldosProductosEnBodegas',
            data: [
                'binventariocontable' => 'T',
                'binventariofisico' => 'T',
                'binventarioproyectado' => 'T',
                'bnombreinventario' => 'T',
            ],
        );

        return $this->normalizeInventoryBalances(data_get($result, 'respuesta.datos'));
    }

    /**
     * Return the product names indexed by SKU using the fixed read-only query.
     */
    public function productNames(): array
    {
        $names = [];

        foreach ($this->sqlRows('select irecurso, nrecurso from invmrec') as $row) {
            $sku = trim((string) $this->rowValue($row, 'irecurso'));
            $name = trim((string) $this->rowValue($row, 'nrecurso'));

            if ($sku !== '' && $name !== '') {
                $names[$sku] = $name;
            }
        }

        return $names;
    }

    /**
     * Return the configured price list indexed by SKU.
     */
    public function productPrices(?string $priceListId = null): array
    {
        $priceListId ??= (string) config('contapyme.price_list_id', '3');

        if (preg_match('/^\d+$/', $priceListId) !== 1) {
            throw new ContaPymeException('El código de la lista de precios debe ser numérico.');
        }

        $prices = [];
        $rows = $this->sqlRows(
            "select irecurso, imetodo, mprecio from invmprecios where ilista = {$priceListId}"
        );

        foreach ($rows as $row) {
            $sku = trim((string) $this->rowValue($row, 'irecurso'));
            $method = trim((string) $this->rowValue($row, 'imetodo'));

            if ($sku === '') {
                continue;
            }

            $prices[$sku] = $method === '1'
                ? $this->number($this->rowValue($row, 'mprecio'))
                : $this->calculatedPrice($sku, $method, $priceListId);
        }

        return $prices;
    }

    /**
     * Return products with their names and the balance from one warehouse.
     */
    public function productsForWarehouse(?string $warehouseId = null): array
    {
        $names = $this->productNames();
        $products = [];

        foreach ($this->stockForWarehouse($warehouseId) as $product) {
            $products[] = [
                'sku' => $product['sku'],
                'name' => $names[$product['sku']] ?? '',
                'accounting_stock' => $product['accounting_stock'],
                'physical_stock' => $product['physical_stock'],
                'projected_stock' => $product['projected_stock'],
            ];
        }

        return $products;
    }

    /**
     * Return stock values for the products present in one warehouse.
     */
    public function stockForWarehouse(?string $warehouseId = null): array
    {
        $warehouseId ??= (string) config('contapyme.warehouse_id', '1');
        $products = [];

        foreach ($this->inventoryBalances() as $product) {
            $sku = trim((string) ($product['irecurso'] ?? ''));
            $warehouses = is_array($product['listabodegas'] ?? null)
                ? $product['listabodegas']
                : [];
            $warehouse = collect($warehouses)->first(
                fn (array $item) => trim((string) ($item['iinventario'] ?? '')) === $warehouseId
            );

            if ($sku === '' || $warehouse === null) {
                continue;
            }

            $products[] = [
                'sku' => $sku,
                'accounting_stock' => $this->number($warehouse['qinvcontable'] ?? 0),
                'physical_stock' => $this->number($warehouse['qinvfisico'] ?? 0),
                'projected_stock' => $this->number($warehouse['qinvproyectado'] ?? 0),
            ];
        }

        return $products;
    }

    /**
     * Return products ready for catalog synchronization.
     */
    public function catalogProductsForWarehouse(
        ?string $warehouseId = null,
        ?string $priceListId = null,
    ): array {
        $prices = $this->productPrices($priceListId);
        $products = [];

        foreach ($this->productsForWarehouse($warehouseId) as $product) {
            $product['price'] = $prices[$product['sku']] ?? null;
            $products[] = $product;
        }

        return $products;
    }

    private function call(string $service, string $method, array $data): array
    {
        $controlKey = $this->controlKey ?? ($this->authenticate()['keyagente'] ?? '');

        try {
            return $this->send($service, $method, $data, (string) $controlKey);
        } catch (ContaPymeException $exception) {
            if ($exception->contaPymeCode !== '40') {
                throw $exception;
            }

            $controlKey = (string) ($this->authenticate()['keyagente'] ?? '');

            return $this->send($service, $method, $data, $controlKey);
        }
    }

    private function send(string $service, string $method, array $data, string $controlKey): array
    {
        $response = Http::acceptJson()
            ->asJson()
            ->connectTimeout(10)
            ->timeout((int) config('contapyme.timeout', 60))
            ->post($this->endpoint($service, $method), [
                '_parameters' => [
                    json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    $controlKey,
                    $this->requiredConfig('iapp'),
                    $this->randomValue(),
                ],
            ]);

        return $this->parseResponse($response, $method);
    }

    private function parseResponse(Response $response, string $method): array
    {
        if (! $response->successful()) {
            throw new ContaPymeException("{$method} respondió HTTP {$response->status()}.");
        }

        $result = data_get($response->json(), 'result.0');

        if (! is_array($result)) {
            throw new ContaPymeException("{$method} devolvió una estructura inesperada.");
        }

        $header = $result['encabezado'] ?? [];

        if (strtolower(trim((string) ($header['resultado'] ?? ''))) !== 'true') {
            $code = trim((string) ($header['imensaje'] ?? ''));
            $message = trim((string) ($header['mensaje'] ?? '')) ?: "{$method} fue rechazado.";

            throw new ContaPymeException("ContaPyme [{$code}]: {$message}", $code);
        }

        return $result;
    }

    private function endpoint(string $service, string $method): string
    {
        return sprintf(
            '%s/datasnap/rest/%s/%s/',
            rtrim($this->requiredConfig('base_url'), '/'),
            $service,
            rawurlencode('"'.$method.'"'),
        );
    }

    private function passwordHash(): string
    {
        $password = $this->requiredConfig('password');

        if (preg_match('/^[a-f0-9]{32}$/i', $password) === 1) {
            return strtolower($password);
        }

        return md5(strtoupper($password));
    }

    private function requiredConfig(string $key): string
    {
        $value = trim((string) config("contapyme.{$key}"));

        if ($value === '') {
            throw new ContaPymeException("Falta configurar contapyme.{$key}.");
        }

        return $value;
    }

    private function normalizeInventoryBalances(mixed $data): array
    {
        if (! is_array($data)) {
            return [];
        }

        if (isset($data[0]['listaproductos']) && is_array($data[0]['listaproductos'])) {
            return $data[0]['listaproductos'];
        }

        if (isset($data['listaproductos']) && is_array($data['listaproductos'])) {
            return $data['listaproductos'];
        }

        if (isset($data[0]['irecurso'])) {
            return $data;
        }

        return [];
    }

    private function normalizeSqlRows(mixed $data): array
    {
        if (! is_array($data)) {
            return [];
        }

        foreach (['datos', 'rows', 'registros'] as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                return $data[$key];
            }
        }

        return array_is_list($data) ? $data : [];
    }

    private function sqlRows(string $sql): array
    {
        $result = $this->call(
            service: 'TBasicoGeneral',
            method: 'GetSql',
            data: ['sql' => $sql],
        );

        return $this->normalizeSqlRows(data_get($result, 'respuesta.datos'));
    }

    private function calculatedPrice(string $sku, string $method, string $priceListId): int|float
    {
        $result = $this->call(
            service: 'TCatElemInv',
            method: 'GetPrecioCalculado',
            data: [
                'irecurso' => $sku,
                'imetodo' => $method,
                'ilista' => $priceListId,
            ],
        );

        return $this->number(data_get($result, 'respuesta.datos.mprecio'));
    }

    private function rowValue(array $row, string $key): mixed
    {
        return $row[$key] ?? $row[strtoupper($key)] ?? null;
    }

    private function number(mixed $value): int|float
    {
        $number = (float) str_replace(',', '.', trim((string) $value));

        return floor($number) === $number ? (int) $number : $number;
    }

    private function randomValue(): string
    {
        return now()->getTimestampMs().random_int(100000, 999999);
    }
}
