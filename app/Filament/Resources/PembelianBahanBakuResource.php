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
                TextInput::make('kode_supplier')
                ->label('kode_supplier')
                ->default(function () {
                    $latest = \App\Models\PembelianBahanBaku::latest('kode_supplier')->first();
                    $lastNumber = $latest ? (int)substr($latest->kode, 1) : 0;
                    $newNumber = $lastNumber + 1;
                    return 'P' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                })
                ->dehydrated()
                ->disabled()
                ->required(),

                Select::make('Supplier')
                    ->label('Supplier')
                    ->relationship('supplier', 'nama')
                    ->searchable()
                    ->required(),

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
                TextColumn::make('supplier.nama')->label('supplier'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime()->label('Tanggal Pembelian'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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