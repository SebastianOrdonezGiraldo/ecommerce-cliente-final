<?php

namespace App\Console\Commands;

use App\Services\ContaPyme\ContaPymeClient;
use App\Services\ContaPyme\ContaPymeException;
use Illuminate\Console\Command;

class TestContaPymeConnection extends Command
{
    protected $signature = 'contapyme:test-connection';

    protected $description = 'Valida la conexión y la lectura de productos desde ContaPyme';

    public function handle(ContaPymeClient $client): int
    {
        try {
            $session = $client->authenticate();
            $products = $client->productsForWarehouse();
        } catch (ContaPymeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Conexión con ContaPyme exitosa.');
        $this->line('Agente: versión '.($session['version'] ?? '?').' release '.($session['release'] ?? '?'));
        $this->line('Productos consumidos en bodega '.config('contapyme.warehouse_id', '1').': '.count($products));

        if ($products !== []) {
            $sample = $products[0];

            $this->table(
                ['SKU', 'Nombre', 'Stock contable', 'Stock proyectado'],
                [[
                    $sample['sku'],
                    $sample['name'],
                    $sample['accounting_stock'],
                    $sample['projected_stock'],
                ]],
            );
        }

        return self::SUCCESS;
    }
}
