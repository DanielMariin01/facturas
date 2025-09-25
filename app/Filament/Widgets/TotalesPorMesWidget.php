<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Facturado;

class TotalesPorMesWidget extends BaseWidget
{
        protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                 Facturado::query()
                ->selectRaw('MIN(id_facturado) as id_facturado, MONTH(fecha_ingreso) as mes, SUM(valor_total) as total')
                    ->whereYear('fecha_ingreso', now()->year)
                    ->groupBy('mes')

            )
            ->columns([
                 Tables\Columns\TextColumn::make('mes')
                    ->label('Mes')
                    ->formatStateUsing(fn ($state) => $this->mesNombre((int) $state))
                    ->sortable(),

                // Total formateado como COP
                Tables\Columns\TextColumn::make('total')
                    ->label('Total (COP)')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 0, ',', '.') . ' COP')
                    ->sortable(),
                 

            ])

             ->filters([
                //Filtro EPS (select con opciones dinámicas)
                Tables\Filters\SelectFilter::make('eps')
                   ->label('EPS')
                    ->options(fn () => Facturado::query()
                        ->select('eps')
                       ->distinct()
                        ->orderBy('eps')
                        ->pluck('eps', 'eps')
                        ->toArray()),

       
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                     'facturado' => 'Facturado',
                       'ingreso_abierto' => 'Ingreso Abierto',
                    'paciente_acostado' => 'Paciente_acostado',
                  
                    'radicado' => 'Radicado',
                ]),
            ])
            ->defaultSort('mes', 'asc');
    }

    private function mesNombre(int $mes): string
{
    $nombres = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];

    return $nombres[$mes] ?? (string) $mes;
}
   
}
