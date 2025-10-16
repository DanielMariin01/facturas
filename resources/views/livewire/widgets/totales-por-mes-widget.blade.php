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
    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
        <thead>
            <tr style="background-color: #00B5B5; color: white;">
                <th style="
                    position: sticky;
                    top: 0;
                    left: 0;
                    z-index: 20;
                    background-color: #00B5B5;
                    padding: 0.5rem 1rem;
                    text-align: left;
                    font-weight: 600;
                    border: 1px solid #D1D5DB;
                    min-width: 120px;
                    max-width: 160px;
                    white-space: normal;
                    word-wrap: break-word;
                ">
                    Convenio
                </th>
                @foreach ($meses as $nombreMes)
                    <th style="
                        position: sticky;
                        top: 0;
                        z-index: 10;
                        background-color: #00B5B5;
                        padding: 0.5rem 1rem;
                        text-align: right;
                        font-weight: 600;
                        border: 1px solid #D1D5DB;
                    ">
                        {{ $nombreMes }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse ($pivotData as $index => $row)
                @php
                    $rowColor = $index % 2 == 0 ? '#f9fafa' : '#eef9f9';
                @endphp
                <tr style="background-color: {{ $rowColor }};"
                    onmouseover="this.style.backgroundColor='#B3EAEA'"
                    onmouseout="this.style.backgroundColor='{{ $rowColor }}'">
                    <!-- Columna fija EPS / Año -->
                    <td style="
                        position: sticky;
                        left: 0;
                        background-color: {{ $rowColor }};
                        z-index: 15;
                        padding: 0.5rem 1rem;
                        font-weight: 500;
                        color: #1F2937;
                        border: 1px solid #D1D5DB;
                        max-width: 160px;
                        white-space: normal;
                        word-wrap: break-word;
                        text-align: left;
                    ">
                        {{ $row['convenio'] }}
                    </td>

                    @foreach ($meses as $nombreMes)
                        <td style="
                            padding: 0.5rem 1rem;
                            text-align: right;
                            color: #1F2937;
                            border: 1px solid #D1D5DB;
                            min-width: 100px;
                        ">
                            @if(isset($row['valores'][$nombreMes]))
                                ${{ number_format($row['valores'][$nombreMes], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($meses) + 1 }}"
                        style="padding: 1rem; text-align: center; color: #6B7280; border: 1px solid #D1D5DB;">
                        No hay datos para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
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
