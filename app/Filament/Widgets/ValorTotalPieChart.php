<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Filament\Support\RawJs;

class ValorTotalPieChart extends ChartWidget
{
    protected static ?string $heading = 'Valor total por Estado';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = '1/3';
    protected static ?string $maxHeight = '250px';

    public ?string $filter = null; // Filtro de mes

    public function mount(): void
    {
        // Establece el filtro en el mes actual al cargar
        $this->filter = (string) now()->month;
    }

    public static function getColumns(): int | array
    {
        return 12;
    }

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'flex justify-center w-full',
            'style' => 'max-width: 350px; margin: auto;',
        ];
    }

    /**
     * 📅 Filtros de meses
     */
    protected function getFilters(): ?array
    {
        return [
            '1'  => 'Enero',
            '2'  => 'Febrero',
            '3'  => 'Marzo',
            '4'  => 'Abril',
            '5'  => 'Mayo',
            '6'  => 'Junio',
            '7'  => 'Julio',
            '8'  => 'Agosto',
            '9'  => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
    }

    /**
     * 📊 Datos del gráfico agrupados por Estado
     */
    protected function getData(): array
    {
        $mesSeleccionado = (int) ($this->filter ?? now()->month);
        $añoActual = now()->year;
        $columnaFecha = 'Fec_Ingreso'; // Cambia si tu campo es diferente

        // 🎨 Colores por estado
        $coloresEstado = [
            'Facturado'          => '#00B5B5',
            'Radicado'           => '#076aff',
            'Ingreso_abierto'    => '#e70d0d',
            'Paciente_acostado'  => '#f4e136',
        ];

        $cacheKey = "valor_total_por_estado_{$añoActual}_{$mesSeleccionado}";

        $data = Cache::remember($cacheKey, 60, function () use ($añoActual, $mesSeleccionado, $columnaFecha) {
            return Facturado::selectRaw('Estado, SUM(Vl_Total) as total')
                ->whereYear($columnaFecha, $añoActual)
                ->whereMonth($columnaFecha, $mesSeleccionado)
                ->groupBy('Estado')
                ->get();
        });

        $labels = $data->pluck('Estado');
        $values = $data->pluck('total');
        $colors = $labels->map(fn ($estado) => $coloresEstado[$estado] ?? '#999999');

        // Si no hay datos, agrega una entrada vacía para evitar error
        if ($labels->isEmpty()) {
            $labels = ['Sin datos'];
            $values = [0.00001];
            $colors = ['#cccccc'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total por Estado',
                    'data' => $values,
                    'backgroundColor' => $colors,
                    'hoverBackgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

 protected function getOptions(): array
{
    return [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
            ],
            'tooltip' => [
                'enabled' => true,

                //'callbacks' => [
                    // 🟢 Función JS correcta para mostrar el valor formateado
                    //'label' => RawJs::make(<<<'JS'
                        //function(context) {
                           // const label = context.label || '';
                            //const value = context.raw || 0;
                           // const formattedValue = new Intl.NumberFormat('es-CO', {
                                //style: 'currency',
                               // currency: 'COP',
                                //minimumFractionDigits: 0
                           // }).format(value);
                            //return `${label}: ${formattedValue}`;
                       // }
                    //JS),
                //],
            ],
        ],
        'scales' => [
            'x' => ['display' => false],
            'y' => ['display' => false],
        ],
        'layout' => [
            'padding' => 10,
        ],
    ];
}


}
