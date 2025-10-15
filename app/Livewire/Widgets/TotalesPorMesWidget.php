<?php

namespace App\Livewire\Widgets;

use Livewire\Component;
use App\Models\Facturado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class TotalesPorMesWidget extends Component
{
    public $estado = '';
    public $epsSeleccionada = '';
    public $convenioSeleccionado = '';
    public $perPage = 10;
    public $paginaActual = 1;

    public function mount()
    {
        $this->paginaActual = 1;
    }



    public function aplicarFiltros()
{
    $this->paginaActual = 1;
    \Log::info("🔍 Aplicando filtros — Estado: {$this->estado}, EPS: {$this->epsSeleccionada}, Convenio: {$this->convenioSeleccionado}");
}

    // 🔹 Cuando cambia el estado, reiniciamos EPS y página
    public function updatedEstado()
    {
        $this->epsSeleccionada = '';
        $this->paginaActual = 1;
        Log::info("✅ Estado cambiado a: {$this->estado}");
    }

    // 🔹 Cuando cambia la EPS, reiniciamos a la primera página
    public function updatedEpsSeleccionada()
    {
        $this->paginaActual = 1;
        Log::info("✅ EPS cambiada a: {$this->epsSeleccionada}");
    }


// 🔹 Cuando cambia el convenio, reiniciamos a la primera página
public function updatedConvenioSeleccionado()
{
    $this->paginaActual = 1;
    \Log::info("✅ Convenio cambiado a: {$this->convenioSeleccionado}");
}




    // 🔹 Cambio manual de página
    public function cambiarPagina($numero)
    {
        $this->paginaActual = max(1, (int) $numero);
        Log::info("📄 Cambiando a página: {$this->paginaActual}");
    }

    // 🔹 Obtener lista de estados (para el select)
    public function getEstados()
    {
        return Facturado::selectRaw('TRIM(Estado) as Estado')
            ->distinct()
            ->orderBy('Estado')
            ->pluck('Estado')
            ->toArray();
    }

    // 🔹 Obtener lista de EPS filtradas por estado (si aplica)
    public function getEps()
    {
        $query = Facturado::selectRaw('TRIM(EPS) as EPS')->distinct();

        if ($this->estado) {
            $query->whereRaw("TRIM(LOWER(Estado)) = ?", [strtolower(trim($this->estado))]);
        }

        return $query->orderBy('EPS')->pluck('EPS')->toArray();
    }

    // 🔹 Obtener lista de convenios filtrados por estado y EPS (si aplica)
//public function getConvenios()
//{
    //$query = \App\Models\Facturado::selectRaw('TRIM(Convenio) as Convenio')->distinct();

    //if ($this->estado) {
        //$query->whereRaw("TRIM(LOWER(Estado)) = ?", [strtolower(trim($this->estado))]);
    //}

    //if ($this->epsSeleccionada) {
       // $query->whereRaw("TRIM(LOWER(EPS)) = ?", [strtolower(trim($this->epsSeleccionada))]);
   // }

   // return $query->orderBy('Convenio')->pluck('Convenio')->toArray();
//}


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
            ->when($this->epsSeleccionada, function ($q) {
                $q->whereRaw("TRIM(LOWER(EPS)) = ?", [strtolower(trim($this->epsSeleccionada))]);
            })
    ->when($this->convenioSeleccionado, function ($q) {
    $valor = strtolower(trim($this->convenioSeleccionado));
    $q->whereRaw("LOWER(TRIM(Convenio)) LIKE ?", ["%{$valor}%"]);
})
            ->groupBy('EPS', DB::raw('YEAR(Fec_Ingreso)'), DB::raw('MONTH(Fec_Ingreso)'))
            ->orderBy('EPS');

        $rawData = $query->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // 🔸 Crear estructura tipo pivot EPS(Año) → [Mes => Total]
        $pivot = [];
        foreach ($rawData as $item) {
            $epsKey = trim($item->EPS) . " ({$item->anio})";
            $mesNombre = $meses[$item->mes] ?? $item->mes;
            $pivot[$epsKey][$mesNombre] = $item->total;
        }
        ksort($pivot);

        $flat = collect($pivot)->map(fn($val, $eps) => [
            'eps' => $eps,
            'valores' => $val,
        ])->values();

        $totalItems = $flat->count();
        $page = max(1, (int) $this->paginaActual);
        $itemsForPage = $flat->forPage($page, $this->perPage);

        $paginator = new LengthAwarePaginator(
            $itemsForPage->values(),
            $totalItems,
            $this->perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        Log::info("♻️ Render ejecutado — Estado: '{$this->estado}' — EPS: '{$this->epsSeleccionada}' — Página: {$page}");

        return view('livewire.widgets.totales-por-mes-widget', [
            'pivotData' => $paginator,
            'meses' => $meses,
            'estados' => $this->getEstados(),
            'epsList' => $this->getEps(),
            //'convenios' => $this->getConvenios(),
        ]);
    }
}
