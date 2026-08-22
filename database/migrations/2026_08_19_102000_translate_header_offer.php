<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $values = [
            'general.content.header_offer.title' => 'Asesoría especializada para elegir tu equipo.',
            'general.content.header_offer.redirection_title' => 'Contáctanos',
            'general.content.header_offer.redirection_link' => '/contact-us',
        ];

        foreach ($values as $code => $value) {
            DB::table('core_config')->updateOrInsert(
                ['code' => $code],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('core_config')->whereIn('code', [
            'general.content.header_offer.title',
            'general.content.header_offer.redirection_title',
            'general.content.header_offer.redirection_link',
        ])->delete();
    }
};
