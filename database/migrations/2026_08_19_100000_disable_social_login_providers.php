<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['facebook', 'twitter', 'google', 'linkedin-openid', 'github'] as $provider) {
            DB::table('core_config')->updateOrInsert(
                ['code' => 'customer.settings.social_login.enable_'.$provider],
                ['value' => '0', 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('core_config')
            ->whereIn('code', collect(['facebook', 'twitter', 'google', 'linkedin-openid', 'github'])->map(fn ($provider) => 'customer.settings.social_login.enable_'.$provider))
            ->delete();
    }
};
