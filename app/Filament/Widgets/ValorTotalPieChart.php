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

        return [
            'datasets' => [
                [
                    'label' => 'Total por Estado',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                    
                        '#2196F3',
                        '#FFC107',
                        '#F44336',
                        '#9C27B0',
                        '#00BCD4',
                        '#FF5722',
                    ],
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
