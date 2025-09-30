<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Schema;

class SyncDatos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-datos';
    protected $description = 'Sincroniza datos desde SQL Server remoto hacia MySQL local';
    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$remotos = DB::connection('sqlsrv')->table('ejecucion')->get();

     DB::connection('mysql')->table('facturado')->delete();


        $this->info("Iniciando sincronización de datos...");
DB::connection('sqlsrv')
    ->table('ejecucion')
    ->orderBy('Dcto')
    ->chunk(1000, function ($registros) {
        // Obtener columnas que existen en MySQL
        $campos_mysql = Schema::connection('mysql')->getColumnListing('facturado');

         $map_estado = [

                'Facturado' => 'Facturado',
                'Ingreso Abierto' => 'Paciente_abierto',
                'Ingreso Abierto - Paciente Acostado'        => 'Paciente_acostado',

             
            ];

        $datos = [];
    foreach ($registros as $dato) {
    // Verifica que 'Dcto' no esté vacío o nulo
    if (!empty($dato->Dcto)) {

        // Inserta solo las columnas que existen en MySQL
        $registro = collect($dato)->only($campos_mysql)->toArray();

        // Mapeo de Estado
        if (isset($registro['Estado'])) {
            $map_estado_lower = [];
            foreach ($map_estado as $k => $v) {
                $map_estado_lower[strtolower(trim($k))] = $v;
            }
            $estado_lower = strtolower(trim($registro['Estado']));
            $registro['Estado'] = $map_estado_lower[$estado_lower] ?? 'Paciente_abierto';
            $registro['Estado'] = substr($registro['Estado'], 0, 50); // ajusta según tamaño de la columna
        }

        $datos[] = $registro;
    }
}


        // Inserta los datos en bloque
        if (!empty($datos)) {
            DB::connection('mysql')->table('facturado')->insert($datos);
        }

        // Liberar memoria
        unset($datos);
        gc_collect_cycles();
    });
    }

    protected $commands = [
    \App\Console\Commands\SyncDatos::class,
];

}
