<?php

namespace App\Filament\Resources\FormscoaResource\Pages;

use App\Filament\Resources\FormscoaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormscoas extends ListRecords
{
    protected static string $resource = FormscoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
