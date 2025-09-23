<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturadoResource\Pages;
use App\Filament\Resources\FacturadoResource\RelationManagers;
use App\Models\Facturado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn as FBadgeColumn;
use Carbon\Carbon;
use App\Enums\Estado;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\TextInput as FTextInput;
use Filament\Forms\Components\Select as FSelect;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redirect;
use Filament\Facades\Filament;

class FacturadoResource extends Resource
{
    protected static ?string $model = Facturado::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                FBadgeColumn::make('dias_facturado')
                    ->label('Días facturado')
                    ->getStateUsing(function ($record) {
                        if ($record->estado !== 'facturado') {
                            return null;
                        }
                        $fechaIngreso = Carbon::parse($record->fecha_ingreso);
                        $dias = $fechaIngreso->diffInHours(Carbon::now()) / 24;
                        return (int) ceil($dias);
                    })
                    ->color(function ($state) {
                        if (is_null($state)) {
                            return 'gray';
                        } elseif ($state <= 1) {
                            return 'success';
                        } elseif ($state <= 3) {
                            return 'warning';
                        }
                        return 'danger';
                    }),

                TextColumn::make('T_Dcto')
                    ->label('Tipo Documento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dcto')
                    ->label('Documento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_ingreso')
                    ->label('Fecha Ingreso')
                    ->date()
                    ->sortable(),

                FBadgeColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        try {
                            return Estado::from($state)->label();
                        } catch (\ValueError $e) {
                            return $state;
                        }
                    })
                    ->color(fn (string $state) => Estado::from($state)->getColor()),

                TextColumn::make('eps')
                    ->label('EPS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ingreso')
                    ->label('Ingreso')
                    ->money('COP', true)
                    ->sortable(),

                TextColumn::make('Tipo_documento')
                    ->label('Tipo Documento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tip_procedimiento')
                    ->label('Tipo Procedimiento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('codigo_procedimiento')
                    ->label('Código Procedimiento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->sortable(),

                TextColumn::make('valor_unitario')
                    ->label('Valor Unitario')
                    ->money('COP', true)
                    ->sortable(),

                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->money('COP', true)
                    ->sortable(),

                TextColumn::make('convenio')
                    ->label('Convenio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('diagnostico')
                    ->label('Diagnóstico')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('servicio')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('dcto')
                    ->form([
                        FTextInput::make('dcto')
                            ->label('Documento')
                            ->placeholder('Buscar por documento'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['dcto'], fn ($q, $dcto) => $q->where('dcto', $dcto));
                    }),

                Filter::make('eps')
                    ->form([
                        FTextInput::make('eps')
                            ->label('EPS')
                            ->placeholder('Buscar por EPS'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['eps'],
                            fn ($q, $eps) => $q->whereRaw(
                                "MATCH (eps) AGAINST (? IN BOOLEAN MODE)",
                                [$eps . '*']
                            )
                        );
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
      BulkAction::make('redirectSelected')
        ->label('Ir a Radicar')
        ->modalSubheading('¿Estás seguro que deseas hacer esto?') 
        ->icon('heroicon-o-arrow-right-circle')
        ->action(function (Collection $records) {
            // Usar id_facturado en lugar de id
            $ids = $records->pluck('id_facturado')->toArray();

            $url = \App\Filament\Resources\RadicadoResource::getUrl('create') 
                   . '?facturado_ids=' . implode(',', $ids);

            return redirect($url);
        })
        ->deselectRecordsAfterCompletion()
        ->requiresConfirmation(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturados::route('/'),
            //'create' => Pages\CreateFacturado::route('/create'),
            'edit' => Pages\EditFacturado::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    return parent::getEloquentQuery()
        ->where('estado', 'facturado'); // 👈 aquí aplicas el filtro permanente
}

}
