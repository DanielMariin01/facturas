<?php

namespace App\Filament\Resources\FacturadoResource\Pages;

use App\Filament\Resources\FacturadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacturados extends ListRecords
{
    protected static string $resource = FacturadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
