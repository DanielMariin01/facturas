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
            FileUpload::make('archivo_xml')
                ->label('Archivo XML')
                ->acceptedFileTypes(['application/xml', 'text/xml','application/pdf']) // <-- Restringe a archivos XML
                ->required() // Hace que el campo sea obligatorio
                ->storeFileNamesIn('nombre_archivo'),
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

        // Nombres facturados (múltiples registros)
        Tables\Columns\TextColumn::make('facturado.nombre')
            ->label('Nombre')
            ->listWithLineBreaks()
            ->limitList(5) 
             // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

        // Tipo Documento de cada facturado
        Tables\Columns\TextColumn::make('facturado.T_Dcto')
            ->label('Tipo Documento')
       ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

        // Documento de cada facturado
        Tables\Columns\TextColumn::make('facturado.dcto')
            ->label('Documento')
            ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

        // Estado con Badge
        Tables\Columns\BadgeColumn::make('facturado.estado')
            ->label('Estado')
            ->formatStateUsing(fn ($state) => \App\Enums\Estado::tryFrom($state)?->label() ?? $state)
            ->color(fn ($state) => \App\Enums\Estado::tryFrom($state)?->getColor() ?? 'gray')
        ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),
        

        // EPS
        Tables\Columns\TextColumn::make('facturado.eps')
            ->label('EPS')
             ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

        // Ingreso
        Tables\Columns\TextColumn::make('facturado.ingreso')
            ->label('Ingreso')
           ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

        // Fecha de ingreso
        Tables\Columns\TextColumn::make('facturado.fecha_ingreso')
            ->label('Fecha Ingreso')
            ->date()
            ->listWithLineBreaks()
            ->limitList(5)   // 👈 solo muestra 3, expande si hay más
            ->expandableLimitedList(),

                
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
