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
use Illuminate\Database\Eloquent\Model;



class FacturadoResource extends Resource
{
    protected static ?string $model = Facturado::class;
  protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
protected static ?int $navigationSort = 2;

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
        return 'primary';
    }
public static function canEdit(Model $record): bool
{
    return false;
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
                        if ($record->Estado !== 'Facturado') {
                            return null;
                        }
                        $fechaIngreso = Carbon::parse($record->Fec_Ingreso);
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

           Tables\Columns\TextColumn::make('Dcto')->sortable()->searchable()->label('Documento'),
                //Tables\Columns\TextColumn::make('Tipo')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('T_Dcto')->sortable()->searchable()->label('Tipo Documento'),
                Tables\Columns\TextColumn::make('Paciente')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('EPS')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Ingreso')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Fec_Ingreso')->date()->sortable(),
                Tables\Columns\TextColumn::make('Fec_Egreso')->date()->sortable(),

                FBadgeColumn::make('Estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        try {
                            return Estado::from($state)->label();
                        } catch (\ValueError $e) {
                            return $state;
                        }
                    })
                    ->color(fn (string $state) => Estado::from($state)->getColor()),

                Tables\Columns\TextColumn::make('Vl_Unit') ->label('Valor Unitario')  ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') ),
                Tables\Columns\TextColumn::make('Vl_Total') ->label('Valor Total')  ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') ),
                Tables\Columns\TextColumn::make('Codi_Proc')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Nombre')->searchable()->searchable(),
                 Tables\Columns\TextColumn::make('Cod_Med'),
                Tables\Columns\TextColumn::make('Medico')->searchable(),
                 Tables\Columns\TextColumn::make('Factura')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Tipo_Documento')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Factura')->date()->sortable(),
                Tables\Columns\TextColumn::make('Fecha')->date()->sortable(),
                Tables\Columns\TextColumn::make('Grup_Cir')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('Cod_Proced')->sortable()->searchable(),
                 Tables\Columns\TextColumn::make('UVR'),
                Tables\Columns\TextColumn::make('Porcentaje'),
                Tables\Columns\TextColumn::make('Cant'),
                 Tables\Columns\TextColumn::make('Tipo_Cargo'),
                Tables\Columns\TextColumn::make('Cod_CC'),
                Tables\Columns\TextColumn::make('Ce_Cos'),
                Tables\Columns\TextColumn::make('Id_Honor'),
                Tables\Columns\TextColumn::make('Honor'),
                Tables\Columns\TextColumn::make('Especialidad'),
                Tables\Columns\TextColumn::make('Convenio_Id'),
                Tables\Columns\TextColumn::make('Convenio'),
                Tables\Columns\TextColumn::make('NIT'),
                 Tables\Columns\TextColumn::make('CodDx'),
                Tables\Columns\TextColumn::make('Diagnostico'),
                Tables\Columns\TextColumn::make('Anato'),
                Tables\Columns\TextColumn::make('PART'),
                Tables\Columns\TextColumn::make('SERVICIO'),
                Tables\Columns\TextColumn::make('Codigo_CUM'),
                Tables\Columns\TextColumn::make('Registro_INVIMA'),
                Tables\Columns\TextColumn::make('Numero_Cita'),
                Tables\Columns\TextColumn::make('Mes_Ingreso'),
                Tables\Columns\TextColumn::make('Año_ingreso'),
                Tables\Columns\TextColumn::make('agrupador'),
                Tables\Columns\TextColumn::make('Mes_Egreso'),
                 Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Creado el' ),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->label('actualizado el' ),

              
            ])
                 ->defaultPaginationPageOption(10) // 👈 Máximo 10 registros por página
        ->paginated([5, 10, 25])

            ->filters([
                Filter::make('Dcto')
    ->form([
        FTextInput::make('Dcto')
            ->label('Documento')
            ->placeholder('Buscar por documento'),
    ])
    ->query(function ($query, array $data) {
        return $query->when(
            $data['Dcto'] ?? null,
            fn ($q, $dcto) => $q->where('Dcto', $dcto)
        );
    }),

                Filter::make('EPS')
                    ->form([
                        FTextInput::make('EPS')
                            ->label('EPS')
                            ->placeholder('Buscar por EPS'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['EPS'],
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
