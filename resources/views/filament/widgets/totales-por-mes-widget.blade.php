<div class="overflow-x-auto w-full">
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
            @foreach($datosPivot as $row)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2 font-medium">{{ $row['eps'] }}</td>
                    @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $mes)
                        <td class="border px-4 py-2 text-right">{{ number_format($row[$mes], 0, ',', '.') }}</td>
                    @endforeach
                    <td class="border px-4 py-2 text-right font-bold bg-gray-50">{{ number_format($row['Total Anual'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
