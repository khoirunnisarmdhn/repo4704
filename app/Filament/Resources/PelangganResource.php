<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1) // Membuat hanya 1 kolom
                ->schema([
                    TextInput::make('id_pelanggan')
                        ->required()
                        ->placeholder('Masukkan id pelanggan')
                    ,
                    TextInput::make('nama_pelanggan')
                        ->required()
                        ->placeholder('Masukkan nama pelanggan')
                    ,
                        TextInput::make('no_telpon_pelanggan')
                        ->required()
                        ->placeholder('Masukkan no telpon pelanggan')
                    ,
                    TextInput::make('alamat_pelanggan')
                        ->autocapitalize('words')
                        ->label('Alamat Pelanggan')
                        ->required()
                        ->placeholder('Masukkan alamat Pelanggan')
                    ,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pelanggan'),
                TextColumn::make('nama_pelanggan'),
                TextColumn::make('no_telpon_pelanggan'), 
                TextColumn::make('alamat_pelanggan'),
            ])
            ->filters([
        
            ])
            ->actions([
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}