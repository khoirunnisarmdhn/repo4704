<?php

namespace App\Filament\Resources;

use App\Filament\Resources\supplierResource\Pages;
use App\Filament\Resources\supplierResource\RelationManagers;
use App\Models\supplier;
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

class supplierResource extends Resource
{
    protected static ?string $model = supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1) // Membuat hanya 1 kolom
                ->schema([
                    TextInput::make('Kode_supplier')
                        ->required()
                        ->placeholder('Masukkan Kode supplier')
                    ,
                    TextInput::make('Nama_supplier')
                        ->required()
                        ->placeholder('Masukkan Nama Supplier')
                    ,
                        TextInput::make('Alamat_supplier')
                        ->required()
                        ->placeholder('Masukkan Alamat supplier')
                    ,
                    TextInput::make('Barang')
                        ->autocapitalize('words')
                        ->label('Barang')
                        ->required()
                        ->placeholder('Masukkan Barang')
                    ,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Kode_supplier'),
                TextColumn::make('Nama_supplier'),
                TextColumn::make('Alamat_supplier'), 
                TextColumn::make('Barang'),
            ])
            ->filters([
        
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
            'index' => Pages\Listsuppliers::route('/'),
            'create' => Pages\Createsupplier::route('/create'),
            'edit' => Pages\Editsupplier::route('/{record}/edit'),
        ];
    }
}  