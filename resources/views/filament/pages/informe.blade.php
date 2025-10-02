<x-filament::page>
    <div class="space-y-6">
        <!-- Aquí se muestra el gráfico -->
        @livewire(\App\Filament\Widgets\ValorTotalPieChart::class)

        <!-- Aquí se muestra la tabla -->
        @livewire(\App\Filament\Widgets\TotalesPorMesWidget::class)
    </div>
</x-filament::page>
