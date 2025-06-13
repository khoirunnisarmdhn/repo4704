<?php

namespace App\Filament\Resources;

use App\Models\PembelianBahanBaku;
use App\Models\Supplier;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\PembelianBahanBakuResource\Pages;

class PembelianBahanBakuResource extends Resource
{
    protected static ?string $model = PembelianBahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';
    protected static ?string $navigationGroup = 'Transaksi';

  public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Select untuk memilih kode_supplier
            Select::make('kode_supplier')
                ->label('Kode Supplier')
                ->relationship('supplier', 'kode_supplier') // Mengambil kode_supplier dari Supplier
                ->required()
                ->searchable()
                ->reactive()  // Agar bisa mengambil barang setelah memilih supplier
                ->afterStateUpdated(function (callable $set, $state) {
                    // Mengambil nilai Barang dari Supplier berdasarkan kode_supplier yang dipilih
                    if ($state) {
                        // Mengambil supplier berdasarkan kode_supplier yang dipilih
                        $supplier = Supplier::where('kode_supplier', $state)->first();
                        // Debugging untuk melihat apakah supplier ditemukan
                        if ($supplier) {
                            // Jika supplier ditemukan, set nilai barang
                            $set('Barang', $supplier->Barang);
                        } else {
                            // Jika tidak ditemukan, kosongkan barang
                            $set('Barang', '');
                        }
                    } else {
                        // Reset barang jika kode_supplier kosong
                        $set('Barang', '');
                    }
                }),

            // Kolom untuk menampilkan barang yang terisi otomatis
            TextInput::make('Barang')  // Menggunakan huruf kecil agar sesuai dengan nama kolom
                ->label('Barang')
                ->disabled()  // Disabled karena otomatis terisi berdasarkan kode_supplier
                ->required(), // Barang tetap wajib diisi meskipun terisi otomatis

            // Select untuk memilih status
            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'selesai' => 'Selesai',
                ])
                ->required(),
        ]);
}

    

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kode_supplier')->sortable()->searchable(),
            TextColumn::make('supplier.nama')->label('Supplier'),
            TextColumn::make('status')->badge(),
            TextColumn::make('created_at')->dateTime()->label('Tanggal Pembelian'),
        ])
        ->actions([
            // Aksi View (melihat detail)
            Tables\Actions\ViewAction::make(),
            // Aksi Edit (untuk mengedit)
            Tables\Actions\EditAction::make(),
            // Aksi Delete (untuk menghapus)
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}


    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
            'edit' => Pages\EditPembelianBahanBaku::route('/{record}/edit'),
           
        ];
    }
}