<div class="w-full px-4 py-4">
    {{-- 🔽 Select para filtrar por estado --}}
    <div class="mb-4">
        <label for="estado" class="block text-sm font-medium text-gray-700">Seleccione un estado:</label>
        <select wire:model.live="estado" id="estado" class="mt-1 block w-1/3 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">-- Seleccione un estado --</option>
            {{-- ✅ CORRECCIÓN: Usamos $estadoOption para evitar conflicto con la propiedad $estado --}}
            @foreach($estados as $estadoOption)
                <option value="{{ $estadoOption }}">{{ $estadoOption }}</option>
            @endforeach
        </select>
    </div>

    {{-- 🔽 Contenido principal --}}
    @if(!$estado)
        <div class="text-center text-gray-500 p-6 border rounded-lg bg-gray-50">
            <p class="text-lg font-medium">⚠️ Seleccione un estado para ver resultados</p>
        </div>
    @else
        <div class="relative">
            {{-- Indicador de carga --}}
            <div wire:loading wire:target="estado" class="absolute inset-0 bg-white bg-opacity-75 z-10 flex items-center justify-center rounded-lg">
                <p class="text-lg font-semibold text-indigo-600">Cargando datos... Por favor, espere. ⏳</p>
            </div>
            
            <div class="overflow-x-auto" wire:loading.remove wire:target="estado">
                <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700 font-semibold">EPS</th>
                            @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                                <th class="border px-4 py-2 text-right text-gray-700 font-semibold">{{ $mes }}</th>
                            @endforeach
                            <th class="border px-4 py-2 text-right text-gray-700 font-bold">Total Anual</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($datosPivot as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2 font-medium">{{ $row['eps'] }}</td>
                                @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                                    <td class="border px-4 py-2 text-right">{{ number_format($row[$mes], 0, ',', '.') }}</td>
                                @endforeach
                                <td class="border px-4 py-2 text-right font-bold bg-gray-50">
                                    {{ number_format($row['Total Anual'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-gray-500 py-6">
                                    No hay resultados para este estado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-4" wire:loading.remove wire:target="estado">
            {{ $datosPivot->links() }}
        </div>
    @endif
</div>