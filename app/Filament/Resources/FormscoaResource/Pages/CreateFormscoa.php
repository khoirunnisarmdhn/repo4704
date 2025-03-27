<?php

namespace App\Filament\Resources\FormscoaResource\Pages;

use App\Filament\Resources\FormscoaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFormscoa extends CreateRecord
{
    protected static string $resource = FormscoaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect ke halaman list setelah create
    }
}
