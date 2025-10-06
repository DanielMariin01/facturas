<?php

namespace App\Livewire\Widgets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Facturado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TotalesPorMesWidget extends Component
{
    use WithPagination;

    public $estado = '';

    public function updatedEstado()
    {


            \Log::info("✅ hola no se cargo: {$this->estado}");
        $this->resetPage(); // resetea paginación
       
    }

    public function getEstados()
    {
        return Facturado::distinct()->pluck('Estado');
    }

    public function render()
    {
        $query = Facturado::selectRaw('
                EPS,
                YEAR(Fec_Ingreso) as anio,
                MONTH(Fec_Ingreso) as mes,
                SUM(Vl_Total) as total
            ')
            ->groupBy('EPS', DB::raw('YEAR(Fec_Ingreso)'), DB::raw('MONTH(Fec_Ingreso)'))
            ->orderBy('EPS')
            ->orderByRaw('YEAR(Fec_Ingreso) ASC')
            ->orderByRaw('MONTH(Fec_Ingreso) ASC');

        if (!empty($this->estado)) {
            $query->whereRaw("TRIM(LOWER(Estado)) = ?", [strtolower(trim($this->estado))]);
        }

        $facturados = $query->paginate(10);

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        foreach ($facturados as $item) {
            $item->mes_nombre = $meses[$item->mes] ?? $item->mes;
        }

        Log::info("♻️ Render ejecutado. Estado actual: {$this->estado}");

        return view('livewire.widgets.totales-por-mes-widget', [
            'facturados' => $facturados,
            'estados' => $this->getEstados(),
        ]);
    }
}
