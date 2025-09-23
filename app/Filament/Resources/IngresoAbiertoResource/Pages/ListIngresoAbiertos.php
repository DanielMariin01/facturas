<?php

namespace App\Filament\Resources\IngresoAbiertoResource\Pages;

use App\Filament\Resources\IngresoAbiertoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngresoAbiertos extends ListRecords
{
    protected static string $resource = IngresoAbiertoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
