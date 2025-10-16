<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ValorTotalPieChart extends ChartWidget
{
    protected static ?string $heading = 'Valor total por Mes';
    protected static bool $isLazy = true;
    protected int | string | array $columnSpan = '1/3';
    protected static ?string $maxHeight = '250px';

    public ?string $filter = null; // Valor inicial nulo, se asigna dinámicamente



public function mount(): void
{
    // Al cargar el widget, establecer el filtro en el mes actual
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
            'style' => 'max-width: 300px; margin: auto;',
        ];
    }

    /**
     * 📅 Filtros por mes
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

    protected function getData(): array
    {
        $mesSeleccionado = (int) ($this->filter ?? now()->month);
        $añoActual = now()->year;

        // 👉 Cambia aquí el nombre de la columna de fecha según tu tabla
        $columnaFecha = 'Fec_Ingreso'; // o 'created_at' si usas timestamps


       $coloresMes = [
            1 => '#00B5B5', // Enero - Turquesa
            2 => '#076aff', // Febrero - Azul
            3 => '#e70d0d', // Marzo - Rojo
            4 => '#f4e136', // Abril - Amarillo
            5 => '#2ecc71', // Mayo - Verde
            6 => '#9b59b6', // Junio - Morado
            7 => '#ff7f50', // Julio - Coral
            8 => '#3498db', // Agosto - Azul claro
            9 => '#e67e22', // Septiembre - Naranja
            10 => '#1abc9c', // Octubre - Verde agua
            11 => '#e84393', // Noviembre - Rosa
            12 => '#34495e', // Diciembre - Gris oscuro
        ];




        $cacheKey = "valor_total_mes_{$añoActual}_{$mesSeleccionado}";

        $totalMes = Cache::remember($cacheKey, 60, function () use ($añoActual, $mesSeleccionado, $columnaFecha) {
            return Facturado::whereYear($columnaFecha, $añoActual)
                ->whereMonth($columnaFecha, $mesSeleccionado)
                ->sum('Vl_Total');
        });

        // Evita error si no hay datos
        if ($totalMes == 0) {
            $totalMes = 0.00001;
        }

        $nombreMes = ucfirst(Carbon::create()->month($mesSeleccionado)->locale('es')->monthName);

        return [
            'datasets' => [
                [
                    'label' => 'Total del mes',
                    'data' => [$totalMes],
                    'backgroundColor' => [$coloresMes[$mesSeleccionado] ?? '#999999'],
                    'hoverBackgroundColor' => [$coloresMes[$mesSeleccionado] ?? '#999999'],

                ],
            ],
            'labels' => [$nombreMes],
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
                'enabled' => true, // ❌ desactiva los números al pasar el mouse
            ],
        ],
        'scales' => [
            'y' => [
                'display' => false, // ❌ oculta los números del eje Y
            ],
            'x' => [
                'display' => false, // ❌ oculta los números del eje X
            ],
        ],
    ];
}

    public static function canView(): bool
{
    return false;
}

}
