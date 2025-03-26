<?php

namespace App\Filament\Resources\FormPegawaiResource\Pages;

use App\Filament\Resources\FormPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPegawais extends ListRecords
{
    protected static string $resource = FormPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
