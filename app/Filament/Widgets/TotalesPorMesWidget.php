<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class TotalesPorMesWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Totales por mes';

   public function getTableRecordKey($record): string
    {
        return (string) $record->id_facturado;
    }

    protected function getTableQuery(): Builder
    {
        // Usamos el Query Builder de Eloquent (no collection!)
        return Facturado::query()
            ->selectRaw('YEAR(Fec_Ingreso) as anio, MONTH(Fec_Ingreso) as mes, SUM(Vl_Total) as total')
            ->groupByRaw('YEAR(Fec_Ingreso), MONTH(Fec_Ingreso)')
            ->orderByRaw('anio desc, mes desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('anio')
                ->label('Año')
                ->sortable(),

            Tables\Columns\TextColumn::make('mes')
                ->label('Mes')
                ->sortable()
                ->formatStateUsing(fn ($state) => str_pad($state, 2, '0', STR_PAD_LEFT)),

            Tables\Columns\TextColumn::make('total')
                ->label('Valor Total')
                ->money('COP', true)
                ->sortable(),
        ];
    }
}
