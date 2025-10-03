<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\Widget;
use Livewire\WithPagination;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log; // Mantenemos el Log para depuración

class TotalesPorMesWidget extends Widget
{
    use WithPagination;

    protected static ?string $heading = 'Totales por mes (EPS vs Meses)';
    protected static string $view = 'filament.widgets.totales-por-mes-widget';
    protected int | string | array $columnSpan = 'full';

    public ?string $estado = null;

    public function updatedEstado()
    {
        $this->resetPage();
        // Log solo el estado seleccionado (los valores de DB pueden ser costosos)
        Log::info('Livewire Estado actualizado: ' . (is_null($this->estado) ? 'NULL' : "'".$this->estado."'"));
    }

    /**
     * Obtiene todos los estados disponibles usando TRIM para limpiar espacios.
     */
    public function getEstados(): array
    {
        return Facturado::query()
            // ✅ CORRECCIÓN 1: Usar TRIM para limpiar los valores del select
            ->select(DB::raw('TRIM(Estado) as Estado'))
            ->distinct()
            ->orderBy('Estado')
            ->pluck('Estado')
            ->toArray();
    }

    /**
     * Genera los datos pivot EPS vs Meses
     */
    public function getDatosPivot(): LengthAwarePaginator
    {
        if (empty($this->estado)) {
            return new LengthAwarePaginator(collect(), 0, 30, $this->page ?? 1);
        }

        $estadoLimpio = trim($this->estado);

        $query = Facturado::query()
            ->selectRaw('EPS, MONTH(Fec_Ingreso) as mes, SUM(Vl_Total) as total')
            // ✅ CORRECCIÓN 2: Usar TRIM en la columna de DB para garantizar la coincidencia
            ->where(DB::raw('TRIM(Estado)'), $estadoLimpio)
            ->groupBy('EPS', DB::raw('MONTH(Fec_Ingreso)'))
            ->orderBy('EPS');
        
        // Ejecución de la consulta
        $data = $query->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $pivoted = [];

        foreach ($data as $row) {
            $eps = $row->EPS;
            $mesNombre = $meses[$row->mes] ?? $row->mes;
            if (!isset($pivoted[$eps])) {
                $pivoted[$eps] = array_merge(['eps' => $eps, 'Total Anual' => 0], array_fill_keys($meses, 0));
            }
            $pivoted[$eps][$mesNombre] = $row->total;
            $pivoted[$eps]['Total Anual'] += $row->total;
        }

        $collection = collect(array_values($pivoted));

        // Paginación con Livewire
        $page = $this->page ?? 1;

        return new LengthAwarePaginator(
            $collection->forPage($page, 10),
            $collection->count(),
            10,
            $page
        );
    }

    /**
     * Renderiza la vista (con la firma correcta)
     */
    public function render(): View
    {
        return view(static::$view, [
            'datosPivot' => $this->getDatosPivot(),
            'estados' => $this->getEstados(),
        ]);
    }

    public static function canView(): bool
    {
        return true;
    }
}