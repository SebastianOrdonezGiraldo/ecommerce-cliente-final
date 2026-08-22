<?php

namespace App\Services\ContaPyme;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Throwable;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class ContaPymeProductSync
{
    private const CATALOG_ATTRIBUTES = [
        'sku',
        'name',
        'url_key',
        'short_description',
        'description',
        'price',
        'weight',
        'status',
        'visible_individually',
        'guest_checkout',
        'manage_stock',
        'tax_category_id',
    ];

    public function __construct(
        private readonly ContaPymeClient $client,
        private readonly ContaPymeTaxConfigurator $taxConfigurator,
        private readonly ProductRepository $productRepository,
        private readonly ProductInventoryRepository $productInventoryRepository,
        private readonly AttributeFamilyRepository $attributeFamilyRepository,
        private readonly InventorySourceRepository $inventorySourceRepository,
    ) {}

    /**
     * Create or update the ContaPyme catalog in Bagisto.
     */
    public function syncCatalog(bool $dryRun = false): array
    {
        $family = $this->requiredFamily();
        $inventorySource = $this->requiredInventorySource();
        $remoteProducts = $this->client->catalogProductsForWarehouse();
        $taxCategory = $dryRun ? null : $this->taxConfigurator->ensure();
        $stats = $this->stats(count($remoteProducts));

        foreach ($remoteProducts as $remoteProduct) {
            $validationError = $this->validateCatalogProduct($remoteProduct);

            if ($validationError !== null) {
                $this->recordError($stats, $remoteProduct, $validationError);

                continue;
            }

            $product = $this->productRepository->findOneByField('sku', $remoteProduct['sku']);

            if ($dryRun) {
                $stats[$product ? 'updated' : 'created']++;

                continue;
            }

            try {
                [$product, $created] = DB::transaction(function () use (
                    $product,
                    $remoteProduct,
                    $family,
                    $inventorySource,
                    $taxCategory,
                ) {
                    $created = $product === null;

                    if ($created) {
                        $product = $this->productRepository->create([
                            'type' => 'simple',
                            'attribute_family_id' => $family->id,
                            'sku' => $remoteProduct['sku'],
                        ]);
                    }

                    $data = $this->catalogData($remoteProduct, $product, $taxCategory->id);
                    $attributes = array_values(array_intersect(self::CATALOG_ATTRIBUTES, array_keys($data)));
                    $product = $this->productRepository->update($data, $product->id, $attributes);
                    $this->saveStock($product, $inventorySource->id, $remoteProduct['projected_stock']);

                    return [$product, $created];
                });

                if ($created) {
                    Event::dispatch('catalog.product.create.after', $product);
                }

                Event::dispatch('catalog.product.update.after', $product);
                $stats[$created ? 'created' : 'updated']++;
            } catch (Throwable $exception) {
                report($exception);
                $this->recordError($stats, $remoteProduct, $exception->getMessage());
            }
        }

        return $stats;
    }

    /**
     * Update only stock for products that already exist in Bagisto.
     */
    public function syncStock(bool $dryRun = false): array
    {
        $inventorySource = $this->requiredInventorySource();
        $remoteProducts = $this->client->stockForWarehouse();
        $stats = $this->stats(count($remoteProducts));

        foreach ($remoteProducts as $remoteProduct) {
            $product = $this->productRepository->findOneByField('sku', $remoteProduct['sku']);

            if ($product === null) {
                $stats['skipped']++;

                continue;
            }

            if ($dryRun) {
                $stats['updated']++;

                continue;
            }

            try {
                $this->saveStock($product, $inventorySource->id, $remoteProduct['projected_stock']);
                Event::dispatch('catalog.product.update.after', $product);
                $stats['updated']++;
            } catch (Throwable $exception) {
                report($exception);
                $this->recordError($stats, $remoteProduct, $exception->getMessage());
            }
        }

        return $stats;
    }

    private function catalogData(array $remoteProduct, object $product, int $taxCategoryId): array
    {
        $name = trim((string) $remoteProduct['name']);
        $data = [
            'sku' => (string) $remoteProduct['sku'],
            'name' => $name,
            'short_description' => $name,
            'description' => $name,
            'price' => (float) $remoteProduct['price'],
            'weight' => 0,
            'status' => 1,
            'visible_individually' => 1,
            'guest_checkout' => 1,
            'manage_stock' => 1,
            'tax_category_id' => $taxCategoryId,
            'channel' => core()->getDefaultChannelCode(),
            'locale' => core()->getDefaultLocaleCodeFromDefaultChannel(),
        ];

        if (blank($product->url_key)) {
            $data['url_key'] = $this->urlKey($name, (string) $remoteProduct['sku']);
        }

        return $data;
    }

    private function saveStock(object $product, int $inventorySourceId, mixed $stock): void
    {
        $this->productInventoryRepository->saveInventories([
            'inventories' => [
                $inventorySourceId => max(0, (int) $stock),
            ],
        ], $product);
    }

    private function validateCatalogProduct(array $product): ?string
    {
        if (blank($product['sku'] ?? null)) {
            return 'SKU vacío.';
        }

        if (blank($product['name'] ?? null)) {
            return 'Nombre vacío.';
        }

        if (! is_numeric($product['price'] ?? null) || (float) $product['price'] <= 0) {
            return 'Precio inexistente o no positivo en la lista configurada.';
        }

        return null;
    }

    private function urlKey(string $name, string $sku): string
    {
        return Str::limit(Str::slug($name.'-'.$sku), 250, '');
    }

    private function requiredFamily(): object
    {
        $code = (string) config('contapyme.attribute_family_code', 'default');
        $family = $this->attributeFamilyRepository->findOneByField('code', $code);

        if ($family === null) {
            throw new ContaPymeException("No existe la familia de atributos '{$code}'.");
        }

        return $family;
    }

    private function requiredInventorySource(): object
    {
        $code = (string) config('contapyme.inventory_source_code', 'default');
        $inventorySource = $this->inventorySourceRepository->findOneByField('code', $code);

        if ($inventorySource === null) {
            throw new ContaPymeException("No existe la fuente de inventario '{$code}'.");
        }

        return $inventorySource;
    }

    private function stats(int $total): array
    {
        return [
            'total' => $total,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
            'messages' => [],
        ];
    }

    private function recordError(array &$stats, array $product, string $message): void
    {
        $stats['errors']++;
        $stats['skipped']++;

        if (count($stats['messages']) < 10) {
            $stats['messages'][] = ($product['sku'] ?? 'sin-sku').': '.$message;
        }
    }
}
