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
            ->title('⚠️ Atención')
            ->body('🚨 Algunos registros tienen Días de ingreso facturado en rojo. significa que el tiempo máximo para radicar la factura está por vencer.
👉 ¡Radica cuanto antes para evitar inconvenientes!. ')
            ->warning() // tipos: ->success(), ->danger(), ->info(), ->warning()
             ->seconds(60)
            ->send();
    }
}
