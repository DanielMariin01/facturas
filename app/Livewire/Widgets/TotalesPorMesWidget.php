<?php

namespace App\Livewire\Widgets;

use Livewire\Component;
use App\Models\Facturado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class TotalesPorMesWidget extends Component
{
    // no usamos WithPagination para evitar conflicto con $page interno
    public $estado = '';
    public $perPage = 10;
    public $paginaActual = 1;

    public function mount()
    {
        $this->paginaActual = 1;
    }

    // al cambiar estado volvemos a la primera página
    public function updatedEstado()
    {
        $this->paginaActual = 1;
        Log::info("✅ updatedEstado ejecutado con valor: {$this->estado}");
    }

    // método para cambiar la página (usado por los botones)
    public function cambiarPagina($numero)
    {
        $numero = (int) $numero;
        if ($numero < 1) {
            $numero = 1;
        }
        $this->paginaActual = $numero;
        Log::info("📄 Cambiando a página: {$this->paginaActual}");
    }

    // helper para obtener estados (!devuelve array)
    public function getEstados()
    {
        return Facturado::selectRaw('TRIM(Estado) as Estado')
            ->distinct()
            ->orderBy('Estado')
            ->pluck('Estado')
            ->toArray();
    }

    public function render()
    {
        // Consulta base (filtra por estado si aplica)
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

        // Pivot: agrupado por "EPS (año)" -> [mes => total]
        $pivot = [];
        foreach ($rawData as $item) {
            $epsKey = trim($item->EPS) . " ({$item->anio})";
            $mesNombre = $meses[$item->mes] ?? $item->mes;
            $pivot[$epsKey][$mesNombre] = $item->total;
        }
        ksort($pivot); // ordenar EPS

        // Convertimos a colección con estructura: [ ['eps' => 'SURA (2024)', 'valores' => [...]], ... ]
        $flat = collect($pivot)->map(function ($val, $eps) {
            return [
                'eps' => $eps,
                'valores' => $val,
            ];
        })->values();

        $totalItems = $flat->count();
        $page = max(1, (int) $this->paginaActual);
        $itemsForPage = $flat->forPage($page, $this->perPage);

        // LengthAwarePaginator con los items ya preparados
        $paginator = new LengthAwarePaginator(
            $itemsForPage->values(), // items de la página
            $totalItems,
            $this->perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        Log::info("♻️ Render ejecutado — Página actual: {$page} — total EPS: {$totalItems} — Estado filtro: '{$this->estado}'");

        return view('livewire.widgets.totales-por-mes-widget', [
            'pivotData' => $paginator, // LengthAwarePaginator
            'meses' => $meses,
            'estados' => $this->getEstados(),
        ]);
    }
}
