<?php

namespace App\Filament\Resources\RadicadoResource\Pages;

use App\Filament\Resources\RadicadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListRadicados extends ListRecords
{
    protected static string $resource = RadicadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        
        ];
    }

       public function mount(): void
    {
        parent::mount();

        Notification::make()
            ->title('Recuerda tus tareas pendientes')
            ->body('Por favor revisa y completa las tareas asignadas antes de continuar.')
            ->warning() // tipos: ->success(), ->danger(), ->info(), ->warning()
             ->seconds(50)
            ->send();
    }

 
}
