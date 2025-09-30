<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Schema;

class SyncDatos extends Command
{
    protected $signature = 'app:sync-datos';
    protected $description = 'Sincroniza datos desde SQL Server remoto hacia MySQL local';

    public function handle()
    {
        $this->info("Iniciando sincronización de datos...");

        // Columnas válidas en MySQL
        $campos_mysql = Schema::connection('mysql')->getColumnListing('facturado');

        // Mapeo de estados en minúsculas
        $map_estado = [
            'facturado' => 'Facturado',
            'ingreso abierto' => 'Ingreso_abierto',
            'ingreso abierto - paciente acostado' => 'Paciente_acostado',
        ];

        DB::connection('sqlsrv')
            ->table('ejecucion')
            ->orderBy('Dcto')
            ->chunk(2000, function ($registros) use ($campos_mysql, $map_estado) {
                $batch = [];

                foreach ($registros as $dato) {
                    if (!empty($dato->Dcto)) {
                        $registro = collect($dato)->only($campos_mysql)->toArray();

                        // Normalizar estado
                        if (isset($registro['Estado'])) {
                            $estadoOriginal = trim($registro['Estado']);
                            $estadoNormalizado = strtolower(preg_replace('/\s+/', ' ', $estadoOriginal)); 
                            // 👆 normaliza espacios y minúsculas

                            $registro['Estado'] = $map_estado[$estadoNormalizado] ?? $estadoOriginal;

                            if (! isset($map_estado[$estadoNormalizado])) {
                                logger()->warning("⚠️ Estado no reconocido: {$estadoOriginal}");
                            }
                        }

                        $batch[] = $registro;

                        // Cuando el lote llega a 300 registros → upsert
                        if (count($batch) >= 300) {
                            DB::connection('mysql')
                                ->table('facturado')
                                ->upsert($batch, ['Dcto']);
                            $batch = []; // limpiar
                        }
                    }
                }

                // Insertar lo que quedó
                if (!empty($batch)) {
                    DB::connection('mysql')
                        ->table('facturado')
                        ->upsert($batch, ['Dcto']);
                }

                unset($batch);
                gc_collect_cycles();
            });

        $this->info("Sincronización finalizada ✅");
    }
}
