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
                Select::make('kode_supplier')
                        ->label('Kode Supplier')
                        ->options(Supplier::all()->pluck('kode_supplier', 'kode_supplier'))
                        ->searchable()
                        ->required()
                ,
                 Select::make('nama_supplier')
                        ->label('Nama Supplier')
                        ->options(Supplier::all()->pluck('nama_supplier', 'nama_supplier'))
                        ->searchable()
                        ->required()
                        ->searchable()
                        ->required()


                   
                ,
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
                TextColumn::make('nama_supplier')->label('Nama Supplier'),
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