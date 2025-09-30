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
        $data = Facturado::selectRaw('Estado, SUM(Vl_Total) as total')
            ->groupBy('Estado')
            ->get();
        
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
}
