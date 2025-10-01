<?php

namespace App\Filament\Widgets;

use App\Models\Facturado;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB; // <- importante

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
        return Facturado::selectRaw('EPS, YEAR(Fec_Ingreso) as anio, MONTH(Fec_Ingreso) as mes, SUM(Vl_Total) as total')
            ->groupBy('EPS', DB::raw('YEAR(Fec_Ingreso)'), DB::raw('MONTH(Fec_Ingreso)'))
            ->orderBy('EPS')
            ->orderByRaw('YEAR(Fec_Ingreso) ASC')
            ->orderByRaw('MONTH(Fec_Ingreso) ASC');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('EPS') // debe coincidir con tu DB
                ->label('EPS')
                ->sortable(),

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
