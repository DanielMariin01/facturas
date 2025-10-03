<?php 

namespace App\Filament\Widgets;

use App\Models\Facturado; 
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;


class ValorTotalPieChart extends ChartWidget
{
    protected static ?string $heading = 'Valor total por Estado';
    protected static bool $isLazy = true;
protected int | string | array $columnSpan = '1/3';

   protected static ?string $maxHeight = '250px';

    public static function getColumns(): int | array
    {
        return 12; // Ocupar toda la fila
    }

   protected function getExtraAttributes(): array
{
    return [
        'class' => 'flex justify-center w-full',
        'style' => 'max-width: 300px; margin: auto;', // lo centra
    ];
}

  
    protected function getData(): array
    {
        // Agrupar por estado y sumar valor_total
           $data = Cache::remember('valor_total_por_estado', 60, function () {
            return Facturado::selectRaw('Estado, SUM(Vl_Total) as total')
                ->groupBy('Estado')
                ->get();
        });

        
               $colores = [
            'Facturado'   => '#17cfb1ff', // verde
            'Radicado'   => '#076affff', // azul
            'Ingreso_abierto'     => '#e70d0dff', // rojo
            'Paciente_acostado'  => '#f4e136ff', // amarillo
        ];
  $backgroundColors = $data->pluck('Estado')->map(function ($estado) use ($colores) {
            return $colores[$estado] ?? '#999999'; // gris si no está definido
        });
        return [
            'datasets' => [
                [
                    'label' => 'Total por Estado',
                    'data' => $data->pluck('total'),
                  'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $data->pluck('Estado'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // tipo gráfico
    }

    public static function canView(): bool
{
    return false;
}



protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'display' => false, // oculta eje X
                    'ticks' => [
                        'display' => false, // oculta números debajo
                    ],
                ],
                'y' => [
                    'display' => false, // oculta eje Y
                    'ticks' => [
                        'display' => false, // oculta números a la izquierda
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true, // deja la leyenda visible
                ],
            ],
        ];
    }


}
