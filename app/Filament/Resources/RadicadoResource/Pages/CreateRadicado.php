<?php

namespace App\Filament\Resources\RadicadoResource\Pages;

use App\Filament\Resources\RadicadoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRadicado extends CreateRecord
{
    protected static string $resource = RadicadoResource::class;

public $facturadoIds = [];

public function mount(): void
{
    if (request()->has('facturado_ids')) {
        $this->facturadoIds = explode(',', request()->get('facturado_ids'));
    }
}

protected function getFormSchema(): array
{
    return [
        Forms\Components\TextInput::make('codigo')->required(),
        Forms\Components\FileUpload::make('archivo')
            ->label('Archivo')
            ->acceptedFileTypes(['application/xml','text/xml','application/pdf'])
            ->required()
            ->storeFileNamesIn('nombre_archivo'),
    ];
}

protected function mutateFormDataBeforeCreate(array $data): array
{
    // Crear el Radicado
    $radicado = \App\Models\Radicado::create($data);

    // Asociar los Facturados seleccionados
    \App\Models\Facturado::whereIn('id_facturado', $this->facturadoIds)
        ->update([
            'fk_radicado' => $radicado->id_radicado,
            'estado' => 'radicado',
        ]);

    return $data;
}

}
