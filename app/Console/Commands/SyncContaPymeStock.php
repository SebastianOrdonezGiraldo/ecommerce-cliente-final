<?php

namespace App\Console\Commands;

use App\Services\ContaPyme\ContaPymeProductSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SyncContaPymeStock extends Command
{
    protected $signature = 'contapyme:sync-stock {--dry-run : Valida sin modificar Bagisto}';

    protected $description = 'Actualiza el stock proyectado de productos existentes desde ContaPyme';

    public function handle(ContaPymeProductSync $sync): int
    {
        $lock = Cache::lock('contapyme:sync-stock', 600);

        if (! $lock->get()) {
            $this->warn('Ya hay una sincronización de stock en curso.');

            return self::INVALID;
        }

        try {
            $stats = $sync->syncStock((bool) $this->option('dry-run'));
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            $lock->release();
        }

        $this->table(
            ['Recibidos', 'Actualizados', 'Omitidos', 'Errores'],
            [[
                $stats['total'],
                $stats['updated'],
                $stats['skipped'],
                $stats['errors'],
            ]],
        );

        foreach ($stats['messages'] as $message) {
            $this->warn($message);
        }

        return $stats['errors'] === 0 ? self::SUCCESS : self::FAILURE;
    }
}
