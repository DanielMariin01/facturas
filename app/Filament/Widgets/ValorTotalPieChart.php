<?php 

namespace App\Filament\Widgets;

use App\Models\Facturado; 
use Filament\Widgets\ChartWidget;

class ValorTotalPieChart extends ChartWidget
{
    protected static ?string $heading = 'Valor total por Estado';

    protected function getData(): array
    {
        // Agrupar por estado y sumar valor_total
        $data = Facturado::selectRaw('estado, SUM(valor_total) as total')
            ->groupBy('estado')
            ->get();
        
               $colores = [
            'facturado'   => '#17cfb1ff', // verde
            'radicado'   => '#076affff', // azul
            'ingreso_abierto'     => '#e70d0dff', // rojo
            'paciente_acostado'  => '#f4e136ff', // amarillo
        ];
  $backgroundColors = $data->pluck('estado')->map(function ($estado) use ($colores) {
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
            'labels' => $data->pluck('estado'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // tipo gráfico
    }
}
