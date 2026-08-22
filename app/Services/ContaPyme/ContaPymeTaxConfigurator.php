<?php

namespace App\Services\ContaPyme;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Tax\Repositories\TaxMapRepository;
use Webkul\Tax\Repositories\TaxRateRepository;

class ContaPymeTaxConfigurator
{
    public function __construct(
        private readonly TaxCategoryRepository $taxCategoryRepository,
        private readonly TaxRateRepository $taxRateRepository,
        private readonly TaxMapRepository $taxMapRepository,
        private readonly CoreConfigRepository $coreConfigRepository,
    ) {}

    /**
     * Ensure that Bagisto treats ContaPyme prices as IVA-inclusive prices.
     */
    public function ensure(): object
    {
        return DB::transaction(function () {
            $category = $this->taxCategoryRepository->updateOrCreate(
                ['code' => (string) config('contapyme.tax_category_code', 'iva-19')],
                [
                    'name' => 'IVA 19%',
                    'description' => 'IVA del 19% incluido en los precios provenientes de ContaPyme.',
                ],
            );

            $rate = $this->taxRateRepository->updateOrCreate(
                ['identifier' => (string) config('contapyme.tax_rate_identifier', 'IVA 19% Colombia')],
                [
                    'is_zip' => 0,
                    'zip_code' => '*',
                    'zip_from' => null,
                    'zip_to' => null,
                    'state' => '*',
                    'country' => (string) config('contapyme.tax_country', 'CO'),
                    'tax_rate' => (float) config('contapyme.tax_rate', 19),
                ],
            );

            $this->taxMapRepository->updateOrCreate([
                'tax_category_id' => $category->id,
                'tax_rate_id' => $rate->id,
            ]);

            foreach ($this->inclusiveTaxConfiguration() as $code => $value) {
                $this->coreConfigRepository->updateOrCreate([
                    'code' => $code,
                    'channel_code' => null,
                    'locale_code' => null,
                ], [
                    'value' => $value,
                ]);
            }

            return $category;
        });
    }

    private function inclusiveTaxConfiguration(): array
    {
        return [
            'sales.taxes.calculation.product_prices' => 'including_tax',
            'sales.taxes.shopping_cart.display_prices' => 'including_tax',
            'sales.taxes.shopping_cart.display_subtotal' => 'including_tax',
            'sales.taxes.sales.display_prices' => 'including_tax',
            'sales.taxes.sales.display_subtotal' => 'including_tax',
        ];
    }
}
