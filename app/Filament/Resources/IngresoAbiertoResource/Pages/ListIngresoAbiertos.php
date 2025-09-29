<?php

namespace App\Filament\Resources\IngresoAbiertoResource\Pages;

use App\Filament\Resources\IngresoAbiertoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListIngresoAbiertos extends ListRecords
{
    protected static string $resource = IngresoAbiertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
