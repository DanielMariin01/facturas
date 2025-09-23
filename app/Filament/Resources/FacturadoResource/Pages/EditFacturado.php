<?php

namespace App\Filament\Resources\FacturadoResource\Pages;

use App\Filament\Resources\FacturadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacturado extends EditRecord
{
    protected static string $resource = FacturadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
