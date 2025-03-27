<?php

namespace App\Filament\Resources\FormProdukResource\Pages;

use App\Filament\Resources\FormProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormProduks extends ListRecords
{
    protected static string $resource = FormProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
