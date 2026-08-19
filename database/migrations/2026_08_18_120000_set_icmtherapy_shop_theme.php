<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('channels')
            ->where('code', 'default')
            ->update(['theme' => 'icmtherapy']);
    }

    public function down(): void
    {
        DB::table('channels')
            ->where('code', 'default')
            ->update(['theme' => 'default']);
    }
};
