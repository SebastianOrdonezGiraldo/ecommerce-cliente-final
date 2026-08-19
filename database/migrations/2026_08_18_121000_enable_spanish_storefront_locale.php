<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $localeId = DB::table('locales')->where('code', 'es')->value('id');

        if (! $localeId) {
            $localeId = DB::table('locales')->insertGetId([
                'code'       => 'es',
                'name'       => 'Español',
                'direction'  => 'ltr',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('channel_locales')->updateOrInsert([
            'channel_id' => 1,
            'locale_id'  => $localeId,
        ]);

        DB::table('channel_translations')->updateOrInsert([
            'channel_id' => 1,
            'locale'     => 'es',
        ], [
            'name'     => 'ICM Therapy',
            'home_seo' => json_encode([
                'meta_title'       => 'ICM Therapy | Bienestar y movimiento',
                'meta_description' => 'Productos para bienestar, movimiento y recuperación.',
                'meta_keywords'    => 'bienestar, fisioterapia, deporte, recuperación',
            ]),
        ]);

        DB::table('channels')->where('code', 'default')->update([
            'default_locale_id' => $localeId,
        ]);
    }

    public function down(): void
    {
        $englishLocaleId = DB::table('locales')->where('code', 'en')->value('id');

        if ($englishLocaleId) {
            DB::table('channels')->where('code', 'default')->update([
                'default_locale_id' => $englishLocaleId,
            ]);
        }
    }
};
