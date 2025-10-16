<div style="width: 100%; padding: 1rem; font-family: Arial, sans-serif;">
    <!-- 🔽 Filtros -->
    <div style="margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; gap: 1.5rem;">
        
        <!-- Filtro Estado -->
        <div>
            <label for="estado"
                style="display: block; font-size: 0.875rem; font-weight: 600; color: #4B5563; margin-bottom: 0.25rem;">
                Estado:
            </label>
            <select wire:model="estado" id="estado"
                style="width: 12rem; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <option value="">Todos</option>
                @foreach ($estados as $itemEstado)
                    <option value="{{ $itemEstado }}">{{ $itemEstado }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro Convenio (texto libre) -->
        <div>
            <label for="convenio"
                style="display: block; font-size: 0.875rem; font-weight: 600; color: #4B5563; margin-bottom: 0.25rem;">
                Convenio:
            </label>
            <input
                type="text"
                id="convenio"
                wire:model.live.debounce.500ms="convenioSeleccionado"
                placeholder="Escriba el nombre del convenio..."
                style="width: 16rem; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
            >
        </div>

        <!-- Filtro EPS -->
        <div>
            <label for="eps"
                style="display: block; font-size: 0.875rem; font-weight: 600; color: #4B5563; margin-bottom: 0.25rem;">
                EPS:
            </label>
            <select wire:model="epsSeleccionada" id="eps"
                style="width: 16rem; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <option value="">Todas</option>
                @foreach ($epsList as $eps)
                    <option value="{{ $eps }}">{{ $eps }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botón Buscar -->
        <div style="display: flex; align-items: flex-end;">
            <button
                wire:click="aplicarFiltros"
                style="
                    background-color: #00B5B5;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 0.5rem;
                    font-weight: 600;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                "
                onmouseover="this.style.backgroundColor='#00A0A0'"
                onmouseout="this.style.backgroundColor='#00B5B5'"
            >
                🔍 Buscar
            </button>
        </div>
    </div>

    <!-- 🧩 Mensaje de carga -->
    <div wire:loading wire:target="aplicarFiltros, cambiarPagina"
        style="margin-bottom: 1rem; color: #2563EB; font-weight: 600; text-align: center;">
        🔄 Cargando datos, por favor espere...
    </div>

    <!-- 🧩 Tabla -->
 <!-- 🧩 Tabla con encabezado fijo -->
<!-- 🧩 Tabla con encabezado y primera columna fijos -->
<!-- 🧩 Tabla con encabezado y primera columna fijos -->
<div style="
    overflow: auto;
    max-height: 500px;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
">
   <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
        <thead>
            <tr>
                <!-- COLUMNA CONVENIO -->
                <th style="
                    position: sticky;
                    top: 0;
                    left: 0;
                    z-index: 20;
                    background-color: #00B5B5;
                    color: white;
                    padding: 0.5rem 1rem;
                    text-align: left;
                    font-weight: 600;
                    border: 1px solid #D1D5DB;
                    min-width: 220px;
                    white-space: normal;
                ">
                    Convenio
                </th>

                <!-- COLUMNAS DE LOS MESES -->
                @foreach ($meses as $nombreMes)
                    <th style="
                        position: sticky;
                        top: 0;
                        z-index: 10;
                        background-color: #00B5B5;
                        color: white;
                        padding: 0.5rem 1rem;
                        text-align: right;
                        font-weight: 600;
                        border: 1px solid #D1D5DB;
                        min-width: 110px;
                    ">
                        {{ $nombreMes }}
                    </th>
                @endforeach

                <!-- COLUMNA TOTAL AÑO -->
                <th style="
                    position: sticky;
                    top: 0;
                    right: 0;
                    z-index: 12;
                    background-color: #008C8C;
                    color: white;
                    padding: 0.5rem 1rem;
                    text-align: right;
                    font-weight: 600;
                    border: 1px solid #D1D5DB;
                    min-width: 130px;
                ">
                    Total Año
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pivotData as $row)
                @php
                    $rowColor = $loop->even ? '#F9FAFB' : '#FFFFFF';
                @endphp
                <tr style="background-color: {{ $rowColor }};">
                    <!-- CONVENIO -->
                    <td style="
                        position: sticky;
                        left: 0;
                        background-color: {{ $rowColor }};
                        font-weight: 500;
                        text-align: left;
                        padding: 0.5rem 1rem;
                        border: 1px solid #D1D5DB;
                        min-width: 220px;
                        white-space: normal;
                        overflow-wrap: break-word;
                    ">
                        {{ $row['convenio'] }}
                    </td>

                    <!-- VALORES POR MES -->
                    @foreach ($meses as $nombreMes)
                        <td style="
                            padding: 0.5rem 1rem;
                            text-align: right;
                            color: #1F2937;
                            border: 1px solid #D1D5DB;
                            min-width: 110px;
                        ">
                            @if(isset($row['valores'][$nombreMes]))
                                ${{ number_format($row['valores'][$nombreMes], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach

                    <!-- TOTAL AÑO -->
                    <td style="
                        position: sticky;
                        right: 0;
                        background-color: #E6FFFA;
                        font-weight: 600;
                        text-align: right;
                        border: 1px solid #D1D5DB;
                        min-width: 130px;
                    ">
                        ${{ number_format($row['total_anual'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <!-- TOTAL GENERAL -->
        <tfoot>
            <tr style="background-color: #DFF7F7; font-weight: bold;">
                <td colspan="{{ count($meses) + 1 }}" style="
                    text-align: right;
                    padding: 0.5rem 1rem;
                    border: 1px solid #D1D5DB;
                ">
                    TOTAL GENERAL:
                </td>
                <td style="
                    position: sticky;
                    right: 0;
                    background-color: #CFF5F5;
                    text-align: right;
                    padding: 0.5rem 1rem;
                    border: 1px solid #D1D5DB;
                    font-weight: 700;
                ">
                    ${{ number_format($pivotData->sum('total_anual'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>




    <!-- 📄 Paginación personalizada -->
    <div style="margin-top: 1rem; display: flex; justify-content: center;">
        <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
            @if ($pivotData->currentPage() > 1)
                <button wire:click="cambiarPagina({{ $pivotData->currentPage() - 1 }})"
                    style="padding: 0.25rem 0.75rem; color: #1F2937; background-color: white; border: 1px solid #D1D5DB; border-radius: 0.375rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#B3EAEA'"
                    onmouseout="this.style.backgroundColor='white'">
                    ‹
                </button>
            @else
                <span style="padding: 0.25rem 0.75rem; color: #9CA3AF; background-color: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 0.375rem;">‹</span>
            @endif

            @php
                $last = $pivotData->lastPage();
                $current = $pivotData->currentPage();
                $start = max(1, $current - 3);
                $end = min($last, $current + 3);
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <span style="padding: 0.25rem 0.75rem; background-color: #00B5B5; color: white; border: 1px solid #00B5B5; border-radius: 0.375rem; font-weight: 600;">
                        {{ $i }}
                    </span>
                @else
                    <button wire:click="cambiarPagina({{ $i }})"
                        style="padding: 0.25rem 0.75rem; background-color: white; color: #1F2937; border: 1px solid #D1D5DB; border-radius: 0.375rem; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='#B3EAEA'"
                        onmouseout="this.style.backgroundColor='white'">
                        {{ $i }}
                    </button>
                @endif
            @endfor

            @if ($pivotData->hasMorePages())
                <button wire:click="cambiarPagina({{ $pivotData->currentPage() + 1 }})"
                    style="padding: 0.25rem 0.75rem; color: #1F2937; background-color: white; border: 1px solid #D1D5DB; border-radius: 0.375rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#B3EAEA'"
                    onmouseout="this.style.backgroundColor='white'">
                    ›
                </button>
            @else
                <span style="padding: 0.25rem 0.75rem; color: #9CA3AF; background-color: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 0.375rem;">›</span>
            @endif
        </div>
    </div>
</div>
