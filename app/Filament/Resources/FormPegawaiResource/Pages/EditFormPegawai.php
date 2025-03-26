<?php

namespace App\Filament\Resources\FormPegawaiResource\Pages;

use App\Filament\Resources\FormPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPegawai extends EditRecord
{
    protected static string $resource = FormPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
