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
    // Muestra el menú solo a admin o gerencia
    return auth()->user()?->hasAnyRole(['admin', 'gerencia']);
}

public function mount(): void
{
    // Bloquea el acceso a quienes no sean admin ni gerencia
    if (! auth()->user()?->hasAnyRole(['admin', 'gerencia'])) {
        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}

}
