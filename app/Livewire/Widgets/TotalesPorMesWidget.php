<?php

namespace App\Livewire\Widgets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Facturado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class TotalesPorMesWidget extends Component
{
    use WithPagination;

    public $estado = '';
    public $perPage = 10;

    public function updatedEstado()
    {
        \Log::info("✅ updatedEstado ejecutado con valor: {$this->estado}");
        $this->resetPage();
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
            ->when($this->estado, function ($q) {
                $q->whereRaw("TRIM(LOWER(Estado)) = ?", [strtolower(trim($this->estado))]);
            })
            ->groupBy('EPS', DB::raw('YEAR(Fec_Ingreso)'), DB::raw('MONTH(Fec_Ingreso)'))
            ->orderBy('EPS');

        $rawData = $query->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // 🔸 Pivot agrupado por EPS y Año
        $pivotData = [];
        foreach ($rawData as $item) {
            $eps = $item->EPS . " ({$item->anio})";
            $mes = $meses[$item->mes] ?? $item->mes;
            $pivotData[$eps][$mes] = $item->total;
        }

        // 🔸 Ordenar EPS alfabéticamente
        ksort($pivotData);

        // 🔸 Paginación manual (10 EPS por página)
        $page = $this->page ?? 1;
        $total = count($pivotData);
        $items = collect($pivotData)->forPage($page, $this->perPage);
        $paginator = new LengthAwarePaginator($items, $total, $this->perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        Log::info("♻️ Render ejecutado. Estado actual: {$this->estado}");

        return view('livewire.widgets.totales-por-mes-widget', [
            'pivotData' => $paginator,
            'meses' => $meses,
            'estados' => $this->getEstados(),
        ]);
    }
}
