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
            @foreach ($estados as $estado)
                <option value="{{ $estado }}">{{ $estado }}</option>
            @endforeach
        </select>
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
                @foreach ($pivotData as $eps => $valores)
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $eps }}</td>
                        @foreach ($meses as $nombreMes)
                            <td class="px-4 py-2 text-right">
                                @if(isset($valores[$nombreMes]))
                                    ${{ number_format($valores[$nombreMes], 0, ',', '.') }}
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

    {{-- 📄 Paginación --}}
    <div class="mt-4">
        {{ $pivotData->links() }}
    </div>
</div>
