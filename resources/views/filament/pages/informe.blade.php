<x-filament::page>
    <div class="space-y-6">
        <!-- 🔹 Widget de Filament (ChartWidget) -->
        @livewire(\App\Filament\Widgets\ValorTotalPieChart::class)

        <!-- 🔹 Componente Livewire normal -->
<livewire:widgets.totales-por-mes-widget :wire:key="'totales-por-mes-'.uniqid()" />

    </div>
</x-filament::page>
