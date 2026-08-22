<?php

return [
    'base_url' => env('CONTAPYME_URL'),
    'email' => env('CONTAPYME_EMAIL'),
    'password' => env('CONTAPYME_PASSWORD'),
    'machine_id' => env('CONTAPYME_MACHINE_ID', '1'),
    'iapp' => env('CONTAPYME_IAPP'),
    'warehouse_id' => env('CONTAPYME_WAREHOUSE_ID', '1'),
    'price_list_id' => env('CONTAPYME_PRICE_LIST_ID', '3'),
    'attribute_family_code' => env('CONTAPYME_ATTRIBUTE_FAMILY_CODE', 'default'),
    'inventory_source_code' => env('CONTAPYME_INVENTORY_SOURCE_CODE', 'default'),
    'tax_category_code' => env('CONTAPYME_TAX_CATEGORY_CODE', 'iva-19'),
    'tax_rate_identifier' => env('CONTAPYME_TAX_RATE_IDENTIFIER', 'IVA 19% Colombia'),
    'tax_country' => env('CONTAPYME_TAX_COUNTRY', 'CO'),
    'tax_rate' => (float) env('CONTAPYME_TAX_RATE', 19),
    'timeout' => (int) env('CONTAPYME_TIMEOUT', 60),
];
