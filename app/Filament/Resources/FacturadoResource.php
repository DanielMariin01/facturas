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

class FacturadoResource extends Resource
{
    protected static ?string $model = Facturado::class;
     public static function canCreate(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                
            ]);
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
        $dias = $fechaIngreso->diffInHours(Carbon::now()) / 24; // diferencia en horas / 24
        return (int) ceil($dias); // 👈 redondea hacia arriba para aproximar
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
    ->formatStateUsing(fn (string $state) => Estado::from($state)->label())
    ->color(fn (string $state) => Estado::from($state)->getColor())
    ->formatStateUsing(function ($state) {
        try {
            return Estado::from($state)->label();
        } catch (\ValueError $e) {
            return $state;
        }
    }),



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
Tables\Filters\Filter::make('dcto')
    ->form([
        \Filament\Forms\Components\TextInput::make('dcto')
            ->label('Documento')
            ->placeholder('Buscar por documento'),
    ])
    ->query(function ($query, array $data) {
        return $query->when(
            $data['dcto'],
            fn ($q, $dcto) => $q->where('dcto', $dcto) // 👈 búsqueda exacta, usa el índice
        );
    }),


   Tables\Filters\Filter::make('eps')
    ->form([
        \Filament\Forms\Components\TextInput::make('eps')
            ->label('EPS')
            ->placeholder('Buscar por EPS'),
    ])
    ->query(function ($query, array $data) {
        return $query->when(
            $data['eps'],
            fn ($q, $eps) => $q->whereRaw(
                "MATCH (eps) AGAINST (? IN BOOLEAN MODE)",
                [$eps . '*'] // prefijo, busca "salud*" → "salud total", "salud sanitas"
            )
        );
    }),



])
->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent) // 👈 esto hace que se muestren arriba



            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturados::route('/'),
            //'create' => Pages\CreateFacturado::route('/create'),
            'edit' => Pages\EditFacturado::route('/{record}/edit'),
        ];
    }
}

