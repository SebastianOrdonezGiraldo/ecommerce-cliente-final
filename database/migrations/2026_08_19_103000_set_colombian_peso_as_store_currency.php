<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $copCurrency = DB::table('currencies')->where('code', 'COP')->first();

        if ($copCurrency) {
            DB::table('currencies')
                ->where('id', $copCurrency->id)
                ->update([
                    'name' => 'Peso colombiano',
                    'symbol' => '$',
                    'decimal' => 0,
                    'group_separator' => '.',
                    'decimal_separator' => ',',
                    'currency_position' => 'left',
                    'updated_at' => now(),
                ]);

            DB::table('channels')->update(['base_currency_id' => $copCurrency->id]);
            DB::table('channel_currencies')
                ->whereIn('currency_id', DB::table('currencies')->where('code', 'USD')->pluck('id'))
                ->delete();

            DB::table('channels')->select('id')->each(function ($channel) use ($copCurrency) {
                DB::table('channel_currencies')->updateOrInsert([
                    'channel_id' => $channel->id,
                    'currency_id' => $copCurrency->id,
                ]);
            });

            return;
        }

        DB::table('currencies')
            ->where('code', 'USD')
            ->update([
                'code' => 'COP',
                'name' => 'Peso colombiano',
                'symbol' => '$',
                'decimal' => 0,
                'group_separator' => '.',
                'decimal_separator' => ',',
                'currency_position' => 'left',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('currencies')
            ->where('code', 'COP')
            ->update([
                'code' => 'USD',
                'name' => 'United States Dollar',
                'symbol' => '$',
                'decimal' => 2,
                'group_separator' => ',',
                'decimal_separator' => '.',
                'currency_position' => null,
                'updated_at' => now(),
            ]);
    }
};
