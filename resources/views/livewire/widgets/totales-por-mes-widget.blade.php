<div class="w-full px-4 py-4">
    {{-- 🔽 Select para filtrar por estado --}}
    <div class="mb-6">
        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">
            Seleccione un estado:
        </label>
        <select wire:model.live="estado"
                id="estado"
                class="mt-1 block w-1/3 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">Todos</option>
            @foreach ($estados as $itemEstado)
                <option value="{{ $itemEstado }}">{{ $itemEstado }}</option>
            @endforeach
        </select>

        {{-- Debug visual opcional --}}
        <div class="mt-2 text-sm text-gray-600">
            Página actual (cliente): {{ $paginaActual ?? '1' }} — Página en paginator: {{ $pivotData->currentPage() }}
        </div>
    </div>

    {{-- 🧩 Tabla pivot con año incluido --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border divide-y divide-gray-200">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">EPS / Año</th>
                    @foreach ($meses as $nombreMes)
                        <th class="px-4 py-2 text-right font-semibold text-gray-700">{{ $nombreMes }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pivotData as $row)
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $row['eps'] }}</td>
                        @foreach ($meses as $nombreMes)
                            <td class="px-4 py-2 text-right">
                                @if(isset($row['valores'][$nombreMes]))
                                    ${{ number_format($row['valores'][$nombreMes], 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 📄 Paginación personalizada (usa cambiarPagina) --}}
    <div class="mt-4 flex justify-center">
        <div class="inline-flex items-center space-x-2">
            {{-- Previous --}}
            @if ($pivotData->currentPage() > 1)
                <button wire:click="cambiarPagina({{ $pivotData->currentPage() - 1 }})"
                        class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-indigo-50">
                    ‹
                </button>
            @else
                <span class="px-3 py-1 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">‹</span>
            @endif

            {{-- Página(s) — ventana alrededor de la actual --}}
            @php
                $last = $pivotData->lastPage();
                $current = $pivotData->currentPage();
                $start = max(1, $current - 3);
                $end = min($last, $current + 3);
            @endphp

            @if ($start > 1)
                <button wire:click="cambiarPagina(1)" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-indigo-50">1</button>
                @if ($start > 2)
                    <span class="px-2 text-gray-500">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <span class="px-3 py-1 bg-indigo-600 text-white border border-indigo-600 rounded font-semibold">{{ $i }}</span>
                @else
                    <button wire:click="cambiarPagina({{ $i }})" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-indigo-50">{{ $i }}</button>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="px-2 text-gray-500">...</span>
                @endif
                <button wire:click="cambiarPagina({{ $last }})" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-indigo-50">{{ $last }}</button>
            @endif

            {{-- Next --}}
            @if ($pivotData->hasMorePages())
                <button wire:click="cambiarPagina({{ $pivotData->currentPage() + 1 }})"
                        class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-indigo-50">
                    ›
                </button>
            @else
                <span class="px-3 py-1 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">›</span>
            @endif
        </div>
    </div>
</div>
