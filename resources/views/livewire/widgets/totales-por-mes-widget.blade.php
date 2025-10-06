<div class="w-full px-4 py-4">
    {{-- 🔽 Select para filtrar por estado --}}
    <div class="mb-4">
        <label for="estado" class="block text-sm font-medium text-gray-700">
            Seleccione un estado:
        </label>
    <select wire:model.live="estado" id="estado"
    class="mt-1 block w-1/3 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    <option value="">Todos</option>
    @foreach ($estados as $item)
        <option value="{{ $item }}">{{ $item }}</option>
    @endforeach
</select>

    </div>

    {{-- 🔽 Tabla de resultados --}}
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">EPS</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Año</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Mes</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($facturados as $f)
                <tr>
                    <td class="px-4 py-2">{{ $f->EPS }}</td>
                    <td class="px-4 py-2">{{ $f->anio }}</td>
                    <td class="px-4 py-2">{{ $f->mes_nombre }}</td>
                    <td class="px-4 py-2 text-right">${{ number_format($f->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $facturados->links() }}
    </div>
</div>
