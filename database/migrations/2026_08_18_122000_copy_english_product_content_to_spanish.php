<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $attributeIds = DB::table('attributes')
            ->whereIn('code', [
                'name',
                'url_key',
                'short_description',
                'description',
                'meta_title',
                'meta_keywords',
                'meta_description',
            ])
            ->pluck('id');

        DB::table('product_attribute_values')
            ->where('locale', 'en')
            ->whereIn('attribute_id', $attributeIds)
            ->orderBy('id')
            ->each(function ($value) {
                $data = Arr::except((array) $value, ['id']);

                $data['locale'] = 'es';
                $data['unique_id'] = implode('|', array_filter([
                    $data['channel'],
                    $data['locale'],
                    $data['product_id'],
                    $data['attribute_id'],
                ], fn ($item) => $item !== null));

                DB::table('product_attribute_values')->updateOrInsert([
                    'channel'      => $data['channel'],
                    'locale'       => $data['locale'],
                    'attribute_id' => $data['attribute_id'],
                    'product_id'   => $data['product_id'],
                ], $data);
            });
    }

    public function down(): void
    {
    }
};
