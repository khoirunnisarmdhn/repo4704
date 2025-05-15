<?php

namespace App\Filament\Resources\StatusPemesananResource\Pages;

use App\Filament\Resources\StatusPemesananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusPemesanan extends EditRecord
{
    protected static string $resource = StatusPemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
