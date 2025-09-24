<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ImagenWidget extends Widget
{
    protected static string $view = 'filament.widgets.imagen-widget';

    public function getColumnSpan(): int|string|array
{
    return [
        'md' => 6,  // ocupa la mitad en pantallas medianas
        'xl' => 6,  // ocupa la mitad en pantallas grandes
    ];
}

}
