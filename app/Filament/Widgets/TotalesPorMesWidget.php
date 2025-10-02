<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TotalesPorMesWidget extends Widget
{
    protected static ?string $heading = 'Totales por mes (EPS vs Meses)';
    protected static string $view = 'filament.widgets.totales-por-mes-widget';
    // En TotalesPorMesWidget.php
protected int | string | array $columnSpan = 'full';


    public static function getColumns(): int | array
    {
        return 12; // Ocupar toda la fila
    }


    // La colección que vamos a pasar a la vista
    public Collection $datosPivot;

    public function mount(): void
    {
        // Traemos los datos agrupados
        $data = Facturado::selectRaw('EPS, MONTH(Fec_Ingreso) as mes, SUM(Vl_Total) as total')
            ->groupBy('EPS', DB::raw('MONTH(Fec_Ingreso)'))
            ->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $pivoted = [];
        foreach ($data as $row) {
            $eps = $row->EPS;
            $mes = $meses[$row->mes] ?? $row->mes;
            $total = $row->total;

            if (!isset($pivoted[$eps])) {
                $pivoted[$eps] = ['eps' => $eps, 'Total Anual' => 0];
                foreach ($meses as $nombreMes) {
                    $pivoted[$eps][$nombreMes] = 0;
                }
            }

            $pivoted[$eps][$mes] = $total;
            $pivoted[$eps]['Total Anual'] += $total;
        }

        $this->datosPivot = collect(array_values($pivoted));
    }


    public static function canView(): bool
{
    return false;
}

}


