<?php
namespace App\Filament\Resources\FormscoaResource\Pages;

use App\Filament\Resources\FormscoaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormscoa extends EditRecord
{
    protected static string $resource = FormscoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(), // Tombol Delete tetap ada
           
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirect ke halaman list setelah edit
    }
}
