<?php

namespace App\Console\Commands;

use App\Services\ContaPyme\ContaPymeProductSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SyncContaPymeCatalog extends Command
{
    protected $signature = 'contapyme:sync-catalog {--dry-run : Valida sin modificar Bagisto}';

    protected $description = 'Crea o actualiza productos, precios y stock desde ContaPyme';

    public function handle(ContaPymeProductSync $sync): int
    {
        $lock = Cache::lock('contapyme:sync-catalog', 3600);

        if (! $lock->get()) {
            $this->warn('Ya hay una sincronización de catálogo en curso.');

            return self::INVALID;
        }

        try {
            $stats = $sync->syncCatalog((bool) $this->option('dry-run'));
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            $lock->release();
        }

        $this->renderStats($stats);

        return $stats['errors'] === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function renderStats(array $stats): void
    {
        $this->table(
            ['Recibidos', 'Crear', 'Actualizar', 'Omitidos', 'Errores'],
            [[
                $stats['total'],
                $stats['created'],
                $stats['updated'],
                $stats['skipped'],
                $stats['errors'],
            ]],
        );

        foreach ($stats['messages'] as $message) {
            $this->warn($message);
        }
    }
}
