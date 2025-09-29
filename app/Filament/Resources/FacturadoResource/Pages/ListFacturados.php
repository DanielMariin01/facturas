<?php

namespace App\Filament\Resources\FacturadoResource\Pages;

use App\Filament\Resources\FacturadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;


class ListFacturados extends ListRecords
{
    protected static string $resource = FacturadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
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
