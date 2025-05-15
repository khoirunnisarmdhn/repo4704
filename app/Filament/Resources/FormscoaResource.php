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

use App\Filament\Resources\FormscoaResource\Pages;
use App\Filament\Resources\FormscoaResource\RelationManagers;
use App\Models\Formscoa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Grid;

class FormscoaResource extends Resource
{
    protected static ?string $model = Formscoa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'contoh form';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_akun')
                    ->label('Kode_Akun')
                    ->required(),

                TextInput::make('nama_akun')
                    ->label('Nama_Akun')
                    ->required(),

                TextInput::make('header_akun')
                    ->label('Header_Akun')
                    ->required(),

                TextInput::make('kode_produk')
                    ->label('Kode_Produk')
                    ->required(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->required(),
                
                FileUpload::make('dokumen')
                    ->label('Dokumen')
                    ->directory('documents')
                    ->columnSpan(2)
                    ->required(),

                FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->directory('images')
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
                TextColumn::make('kode_akun')
                ->label('Kode_Akun')
                ->searchable()
                ->sortable(),

            TextColumn::make('nama_akun')
                ->label('Nama_Akun')
                ->searchable()
                ->sortable(),

            TextColumn::make('header_akun')
                ->label('Header_Akun')
                ->searchable()
                ->sortable(),

            TextColumn::make('kode_produk')
                ->label('Kode_Produk')
                ->searchable()
                ->sortable(),

            TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->sortable(),

            TextColumn::make('dokumen')
                ->label('Dokumen')
                ->url(fn($record) => asset('storage/' . $record->file_path), true)
                ->formatStateUsing(fn($state) => $state 
                    ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><i class="fas fa-file-pdf"></i> 📄 </a>' 
                    : 'Tidak Ada File')
                ->html(), // Pastikan menggunakan html() agar bisa merender HTML
                // Buka file saat diklik

            ImageColumn::make('gambar') // Menampilkan gambar di tabel
                ->label('Gambar')
                ->size(50), // Menyesuaikan ukuran thumbnail

            IconColumn::make('is_admin')
                ->label('Admin?')
                ->boolean(),
            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFormscoas::route('/'),
            'create' => Pages\CreateFormscoa::route('/create'),
            'edit' => Pages\EditFormscoa::route('/{record}/edit'),
        ];
    }
}
