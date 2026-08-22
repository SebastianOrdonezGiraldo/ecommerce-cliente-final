<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['facebook', 'twitter', 'google', 'linkedin-openid'] as $provider) {
            DB::table('core_config')
                ->where('code', 'customer.settings.social_login.enable_'.$provider)
                ->update(['value' => '1', 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        foreach (['facebook', 'twitter', 'google', 'linkedin-openid'] as $provider) {
            DB::table('core_config')
                ->where('code', 'customer.settings.social_login.enable_'.$provider)
                ->update(['value' => '0', 'updated_at' => now()]);
        }
    }
};
