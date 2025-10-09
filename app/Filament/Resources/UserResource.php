<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;



class UserResource extends Resource
{
    protected static ?string $model = User::class;


     protected static ?string $navigationLabel = 'Usuarios';
         protected static ?string $navigationIcon = 'heroicon-o-users';
      protected static ?string $navigationGroup = 'Administración';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                     Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
          
                   Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Correo'),
                   Forms\Components\TextInput::make('password')
                    ->required()
                    ->label('Contraseña'),

             Forms\Components\Select::make('roles')
    ->label('Rol')
    ->relationship('roles', 'name')
    ->preload()
    ->multiple(false)
    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('roles.name')
                ->label('Rol'),
              
                    //Tables\Columns\TextColumn::make('password')
                    //->label('Contraseña')
                    //->searchable()
                    //->sortable(),
                    Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de Creación'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Fecha de Actualización'),
          
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }


        public static function shouldRegisterNavigation(): bool
{
    // Oculta del menú a todos los que no sean admin
    return auth()->user()?->hasRole('admin');
}

public function mount(): void
{
    // Bloquea el acceso directo a la URL si no es admin
    if (! auth()->user()?->hasRole('admin')) {
        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}

}
