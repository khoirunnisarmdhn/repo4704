<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use Filament\Actions;
use App\Models\PembelianBahanBaku;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PembelianBahanBakuResource;

class EditPembelianBahanBaku extends EditRecord
{
 protected static string $resource = PembelianBahanBakuResource::class;

    public function mount($record): void
    {
        // Pastikan record diambil dengan benar
        $this->record = PembelianBahanBaku::findOrFail($record);
    }
}
