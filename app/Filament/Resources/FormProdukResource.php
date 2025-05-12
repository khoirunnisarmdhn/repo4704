<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

use App\Filament\Resources\FormProdukResource\Pages;
use App\Filament\Resources\FormProdukResource\RelationManagers;
use App\Models\FormProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Grid;

class FormProdukResource extends Resource
{
    protected static ?string $model = FormProduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_produk')
                    ->label('Nama Produk')
                    ->required(),

                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->directory('images')
                    ->required(),

                FileUpload::make('dokumen')
                    ->label('Dokumen')
                    ->directory('documents')
                    ->columnSpan(2)
                    ->required(),

                Toggle::make('is_admin')
                    ->label('Admin?')
                    ->inline(false)
                    ->columnSpan(2)
                    ->required(),

         
             ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nama_produk')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('jenis_produk')
                ->label('Jenis Produk')
                ->sortable(),

            TextColumn::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->sortable(),

            TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->sortable(),

            ImageColumn::make('gambar') // Menampilkan gambar di tabel
                ->label('Gambar')
                ->size(50), // Menyesuaikan ukuran thumbnail
            
            TextColumn::make('dokumen')
                ->label('Dokumen')
                ->url(fn($record) => asset('storage/' . $record->file_path), true)
                ->formatStateUsing(fn($state) => $state 
                    ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><i class="fas fa-file-pdf"></i> 📄 </a>' 
                    : 'Tidak Ada File')
                ->html(), // Pastikan menggunakan html() agar bisa merender HTML
                // Buka file saat diklik

            IconColumn::make('is_admin')
                ->label('Admin?')
                ->boolean(),

            TextColumn::make('content')
                ->label('content')
                ->limit(50),
                // ->tooltip(fn($record) => $record->content),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListFormProduks::route('/'),
            'create' => Pages\CreateFormProduk::route('/create'),
            'edit' => Pages\EditFormProduk::route('/{record}/edit'),
        ];
    }
}