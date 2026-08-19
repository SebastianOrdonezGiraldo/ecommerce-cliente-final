<?php

namespace Tests\Feature;

use App\Services\ContaPyme\ContaPymeClient;
use App\Services\ContaPyme\ContaPymeProductSync;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;
use Webkul\Product\Repositories\ProductRepository;

class ContaPymeProductSyncTest extends TestCase
{
    public function test_it_creates_and_then_updates_a_product_by_sku(): void
    {
        Event::fake();

        config()->set('contapyme.attribute_family_code', 'default');
        config()->set('contapyme.inventory_source_code', 'default');
        config()->set('contapyme.tax_category_code', 'iva-19');
        config()->set('contapyme.tax_rate_identifier', 'IVA 19% Colombia');
        config()->set('contapyme.tax_country', 'CO');
        config()->set('contapyme.tax_rate', 19);

        $initialProductCount = DB::table('products')->count();
        $sku = 'CP-'.str()->random(12);
        $initial = $this->remoteProduct($sku, 147000, 10);
        $updated = $this->remoteProduct($sku, 160000, 7);
        $client = Mockery::mock(ContaPymeClient::class);
        $client->shouldReceive('catalogProductsForWarehouse')
            ->twice()
            ->andReturn([$initial], [$updated]);
        app()->instance(ContaPymeClient::class, $client);

        $sync = app(ContaPymeProductSync::class);
        $createdStats = $sync->syncCatalog();
        $updatedStats = $sync->syncCatalog();

        $this->assertSame(1, $createdStats['created']);
        $this->assertSame(1, $updatedStats['updated']);

        $product = app(ProductRepository::class)->findOneByField('sku', $sku);

        $this->assertNotNull($product);
        $this->assertSame(160000.0, (float) $product->price);
        $this->assertSame(1, (int) $product->status);
        $this->assertSame(1, (int) $product->visible_individually);
        $this->assertSame('Producto ContaPyme', $product->name);
        $this->assertSame(7, (int) DB::table('product_inventories')
            ->where('product_id', $product->id)
            ->value('qty'));
        $this->assertSame('including_tax', DB::table('core_config')
            ->where('code', 'sales.taxes.calculation.product_prices')
            ->value('value'));
        $this->assertDatabaseCount('products', $initialProductCount + 1);
    }

    private function remoteProduct(string $sku, int $price, int $stock): array
    {
        return [
            'sku' => $sku,
            'name' => 'Producto ContaPyme',
            'accounting_stock' => $stock,
            'physical_stock' => $stock,
            'projected_stock' => $stock,
            'price' => $price,
        ];
    }
}
