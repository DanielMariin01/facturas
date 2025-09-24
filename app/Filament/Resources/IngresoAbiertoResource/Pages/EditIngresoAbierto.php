<?php

namespace App\Filament\Resources\IngresoAbiertoResource\Pages;

use App\Filament\Resources\IngresoAbiertoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIngresoAbierto extends EditRecord
{
    protected static string $resource = IngresoAbiertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }
}
