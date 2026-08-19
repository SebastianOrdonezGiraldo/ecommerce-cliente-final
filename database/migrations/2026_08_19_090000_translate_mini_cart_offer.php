<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $code = 'sales.checkout.mini_cart.offer_info';
        $value = 'Recibe asesoría para elegir el equipo ideal para tu recuperación.';

        if (DB::table('core_config')->where('code', $code)->exists()) {
            DB::table('core_config')->where('code', $code)->update(['value' => $value]);

            return;
        }

        DB::table('core_config')->insert([
            'code' => $code,
            'value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('core_config')
            ->where('code', 'sales.checkout.mini_cart.offer_info')
            ->update(['value' => 'Get Up To 30% OFF on your 1st order']);
    }
};
