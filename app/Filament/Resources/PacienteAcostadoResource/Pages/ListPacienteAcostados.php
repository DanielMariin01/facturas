<?php

namespace App\Filament\Resources\PacienteAcostadoResource\Pages;

use App\Filament\Resources\PacienteAcostadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListPacienteAcostados extends ListRecords
{
    protected static string $resource = PacienteAcostadoResource::class;

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
