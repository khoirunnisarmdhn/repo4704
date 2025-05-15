<?php

namespace App\Filament\Resources\StatusPemesananResource\Pages;

use App\Filament\Resources\StatusPemesananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusPemesanans extends ListRecords
{
    protected static string $resource = StatusPemesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
