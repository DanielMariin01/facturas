<?php

namespace App\Filament\Resources\PacienteAcostadoResource\Pages;

use App\Filament\Resources\PacienteAcostadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPacienteAcostado extends EditRecord
{
    protected static string $resource = PacienteAcostadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
