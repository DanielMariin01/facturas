<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Informe extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
      protected static ?string $navigationGroup = 'Administración';

    protected static string $view = 'filament.pages.informe';


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
