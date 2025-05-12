<?php

namespace App\Filament\Resources\FormProdukResource\Pages;

use App\Filament\Resources\FormProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormProduk extends EditRecord
{
    protected static string $resource = FormProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
