<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusPemesananResource\Pages;
use App\Filament\Resources\StatusPemesananResource\RelationManagers;
use App\Models\StatusPemesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class StatusPemesananResource extends Resource
{
    protected static ?string $model = StatusPemesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationLabel = 'Status Pemesanan';
    protected static ?string $modelLabel = 'Status Pemesanan';
    protected static ?string $pluralModelLabel = 'Status Pemesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('id_penjualan')
                ->label('No Faktur Penjualan')
                ->relationship('penjualan', 'no_faktur') // pastikan kolom no_faktur tersedia
                ->required(),
            Select::make('status')
                ->options([
                    'proses' => 'Proses',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ])
                ->required()
                ->label('Status'),
            Textarea::make('keterangan')
                ->label('Keterangan')
                ->rows(3)
                ->placeholder('Catatan tambahan')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('penjualan.no_faktur')
                ->label('No Faktur')
                ->searchable(),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'proses' => 'warning',
                    'selesai' => 'success',
                    'batal' => 'danger',
                }),
            TextColumn::make('keterangan')
                ->label('Keterangan')
                ->limit(30),
        ])->defaultSort('id_status', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatusPemesanans::route('/'),
            'create' => Pages\CreateStatusPemesanan::route('/create'),
            'edit' => Pages\EditStatusPemesanan::route('/{record}/edit'),
        ];
    }
}
