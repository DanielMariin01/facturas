<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RadicadoResource\Pages;
use App\Filament\Resources\RadicadoResource\RelationManagers;
use App\Models\Radicado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput as FTextInput;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Model;



class RadicadoResource extends Resource
{
    protected static ?string $model = Radicado::class;

    
 public static function getNavigationBadge(): ?string
    {
        // Esto reflejará el conteo de registros visibles bajo el scope getEloquentQuery()
        return static::getEloquentQuery()->count();
    }

    // Color del contador: NARANJA
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary'; // Cambiado a 'warning' para el color naranja
    }
   protected static ?string $navigationIcon = 'heroicon-o-inbox';

protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 TextInput::make('codigo')
                ->label('Codigo'),

            // Campo para subir archivos
        FileUpload::make('archivo')
    ->label('Archivo TXT')
    ->acceptedFileTypes(['text/plain']) // Solo archivos .txt
    ->required() // Campo obligatorio
    ->storeFileNamesIn('nombre_archivo')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

      Tables\Columns\TextColumn::make('id_radicado')
            ->label('ID Radicado')
            ->sortable(),

        Tables\Columns\TextColumn::make('codigo')
            ->label('Código Radicado')
            ->searchable(),

     
        Tables\Columns\BadgeColumn::make('facturado.estado')
            ->label('Estado')
            ->formatStateUsing(fn ($state) => \App\Enums\Estado::tryFrom($state)?->label() ?? $state)
            ->color(fn ($state) => \App\Enums\Estado::tryFrom($state)?->getColor() ?? 'gray')
        ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),
        

           Tables\Columns\TextColumn::make('facturado.Dcto')->sortable()->searchable()->label('Documento'),
                //Tables\Columns\TextColumn::make('Tipo')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('facturado.T_Dcto')->sortable()->searchable()->label('Tipo Documento'),
                Tables\Columns\TextColumn::make('facturado.Paciente')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('facturado.EPS')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('facturado.Ingreso')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('facturado.Fec_Ingreso')->date()->sortable(),
                Tables\Columns\TextColumn::make('facturado.Fec_Egreso')->date()->sortable(),
                 Tables\Columns\TextColumn::make('facturado.Vl_Unit') ->label('Valor Unitario')  ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') ),
                Tables\Columns\TextColumn::make('facturado.Vl_Total') ->label('Valor Total')  ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') ),
                Tables\Columns\TextColumn::make('facturado.Codi_Proc')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('facturado.Nombre')->searchable()->searchable(),
                 //Tables\Columns\TextColumn::make('facturado.Cod_Med'),
               // Tables\Columns\TextColumn::make('facturado.Medico')->searchable(),
                 //Tables\Columns\TextColumn::make('facturado.Factura')->sortable()->searchable(),
                //Tables\Columns\TextColumn::make('facturado.Tipo_Documento')->sortable()->searchable(),
                //Tables\Columns\TextColumn::make('facturado.Fecha_Factura')->date()->sortable(),
                Tables\Columns\TextColumn::make('facturado.Fecha')->date()->sortable(),
                //Tables\Columns\TextColumn::make('facturado.Grup_Cir')->sortable()->searchable(),
                //Tables\Columns\TextColumn::make('facturado.Cod_Proced')->sortable()->searchable(),
                 //Tables\Columns\TextColumn::make('facturado.UVR'),
                //Tables\Columns\TextColumn::make('facturado.Porcentaje'),
                Tables\Columns\TextColumn::make('facturado.Cant'),
                 Tables\Columns\TextColumn::make('facturado.Tipo_Cargo'),
                //Tables\Columns\TextColumn::make('facturado.Cod_CC'),
                //Tables\Columns\TextColumn::make('facturado.Ce_Cos'),
                //Tables\Columns\TextColumn::make('facturado.Id_Honor'),
                //Tables\Columns\TextColumn::make('facturado.Honor'),
                //Tables\Columns\TextColumn::make('facturado.Especialidad'),
                Tables\Columns\TextColumn::make('facturado.Convenio_Id'),
                Tables\Columns\TextColumn::make('facturado.Convenio'),
                Tables\Columns\TextColumn::make('facturado.NIT'),
                 //Tables\Columns\TextColumn::make('facturado.CodDx'),
                Tables\Columns\TextColumn::make('facturado.Diagnostico'),
                //Tables\Columns\TextColumn::make('facturado.Anato'),
                //Tables\Columns\TextColumn::make('facturado.PART'),
                Tables\Columns\TextColumn::make('facturado.SERVICIO'),
                //Tables\Columns\TextColumn::make('facturado.Codigo_CUM'),
                //Tables\Columns\TextColumn::make('facturado.Registro_INVIMA'),
               //Tables\Columns\TextColumn::make('facturado.Numero_Cita'),
                //Tables\Columns\TextColumn::make('facturado.Mes_Ingreso'),
                //Tables\Columns\TextColumn::make('facturado.Año_ingreso'),
                //Tables\Columns\TextColumn::make('facturado.agrupador'),
                Tables\Columns\TextColumn::make('facturado.Mes_Egreso'),
    
       
            ])
             ->defaultPaginationPageOption(10) // 👈 Máximo 10 registros por página
        ->paginated([5,10, 25])


    ->headerActions([
         Tables\Actions\CreateAction::make()
                ->visible(false),
        ])


            ->filters([
                   Filter::make('codigo')
                    ->form([
                        FTextInput::make('codigo')
                            ->label('Codigo')
                            ->placeholder('Buscar por ID de radicacion'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['codigo'], fn ($q, $dcto) => $q->where('codigo', $dcto));
                    }),
            ])
                   ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListRadicados::route('/'),
            'create' => Pages\CreateRadicado::route('/create'),
            //'edit' => Pages\EditRadicado::route('/{record}/edit'),
        ];
    }
   

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    return parent::getEloquentQuery()
        ->whereHas('facturado', function ($query) {
            $query->where('estado', 'radicado');
        });
}

public static function canEdit(Model $record): bool
{
    return false;
}



}
