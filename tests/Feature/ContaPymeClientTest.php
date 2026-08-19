<?php

namespace Tests\Feature;

use App\Services\ContaPyme\ContaPymeClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContaPymeClientTest extends TestCase
{
    public function test_it_loads_products_using_balances_and_the_fixed_name_query(): void
    {
        config()->set('contapyme', [
            'base_url' => 'http://contapyme.test:9000',
            'email' => 'test@example.com',
            'password' => 'c2c5a7005e3465e2090d681de74797e7',
            'machine_id' => '1',
            'iapp' => '1003',
            'warehouse_id' => '1',
            'timeout' => 10,
        ]);

        Http::fakeSequence()
            ->push($this->successfulResponse([
                'keyagente' => 'test-key',
                'version' => '4',
                'release' => '8',
            ]))
            ->push($this->successfulResponse([
                ['irecurso' => 'SKU-1', 'nrecurso' => 'Producto de prueba'],
            ]))
            ->push($this->successfulResponse([[
                'listaproductos' => [[
                    'irecurso' => 'SKU-1',
                    'listabodegas' => [[
                        'iinventario' => '1',
                        'qinvcontable' => '10',
                        'qinvfisico' => '9',
                        'qinvproyectado' => '8',
                    ]],
                ]],
            ]]));

        $products = app(ContaPymeClient::class)->productsForWarehouse();

        $this->assertSame([[
            'sku' => 'SKU-1',
            'name' => 'Producto de prueba',
            'accounting_stock' => 10,
            'physical_stock' => 9,
            'projected_stock' => 8,
        ]], $products);

        Http::assertSent(function ($request) {
            $parameters = $request->data()['_parameters'];
            $data = json_decode($parameters[0], true);

            return str_contains($request->url(), 'GetSql')
                && $data === ['sql' => 'select irecurso, nrecurso from invmrec'];
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'GetSaldosProductosEnBodegas');
        });
    }

    public function test_it_loads_manual_prices_from_the_configured_price_list(): void
    {
        config()->set('contapyme', [
            'base_url' => 'http://contapyme.test:9000',
            'email' => 'test@example.com',
            'password' => 'c2c5a7005e3465e2090d681de74797e7',
            'machine_id' => '1',
            'iapp' => '1003',
            'price_list_id' => '3',
            'timeout' => 10,
        ]);

        Http::fakeSequence()
            ->push($this->successfulResponse([
                'keyagente' => 'test-key',
            ]))
            ->push($this->successfulResponse([
                ['irecurso' => 'SKU-1', 'imetodo' => '1', 'mprecio' => '147000'],
            ]));

        $prices = app(ContaPymeClient::class)->productPrices();

        $this->assertSame(['SKU-1' => 147000], $prices);

        Http::assertSent(function ($request) {
            $parameters = $request->data()['_parameters'];
            $data = json_decode($parameters[0], true);

            return str_contains($request->url(), 'GetSql')
                && $data === [
                    'sql' => 'select irecurso, imetodo, mprecio from invmprecios where ilista = 3',
                ];
        });
    }

    private function successfulResponse(array $data): array
    {
        return [
            'result' => [[
                'encabezado' => [
                    'resultado' => 'true',
                    'imensaje' => '',
                    'mensaje' => '',
                ],
                'respuesta' => ['datos' => $data],
            ]],
        ];
    }
}
