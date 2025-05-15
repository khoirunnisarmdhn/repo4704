<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Filament\Resources\BahanBakuResource\RelationManagers;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([ 
                TextInput::make('Kode_bahanbaku')
                ->required()
                ->placeholder('Masukkan kode bahanbaku')
            ,
            TextInput::make('Nama_bahanbaku')
                ->autocapitalize('words')
                ->label('Nama bahanbaku')
                ->required()
                ->placeholder('Masukkan nama bahanbaku')
            ,
            TextInput::make('Satuan')
                ->autocapitalize('words')
                ->label('Satuan')
                ->required()
                ->placeholder('Masukkan satuan')
            ,
            TextInput::make('Stok')
                ->autocapitalize('words')
                ->label('Stok')
                ->required()
                ->placeholder('Masukkan jumlah_stok')
            ,
            TextInput::make('Harga_perunit')
                ->autocapitalize('words')
                ->label('Harga_perunit')
                ->required()
                ->placeholder('Masukkan harga perunit')
            ,
            TextInput::make('Kode_supplier')
                ->autocapitalize('words')
                ->label('Kode supplier')
                ->required()
                ->placeholder('Masukkan Kode Supplier')
            ,
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Kode_bahanbaku'),
                TextColumn::make('Nama_bahanbaku'),
                TextColumn::make('Satuan'), 
                TextColumn::make('Stok'),
                TextColumn::make('Harga_perunit'),
                TextColumn::make('Kode_supplier'),
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
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
