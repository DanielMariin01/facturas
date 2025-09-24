<?php

namespace App\Filament\Resources\PacienteAcostadoResource\Pages;

use App\Filament\Resources\PacienteAcostadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacienteAcostados extends ListRecords
{
    protected static string $resource = PacienteAcostadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
