<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
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

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1) // Membuat hanya 1 kolom
                ->schema([
                    TextInput::make('id_pegawai')
                        ->required()
                        ->placeholder('Masukkan id pegawai')
                    ,
                    TextInput::make('nama_pegawai')
                        ->required()
                        ->placeholder('Masukkan nama pegawai')
                    ,
                        TextInput::make('no_telpon_pegawai')
                        ->required()
                        ->placeholder('Masukkan no telpon pegawai')
                    ,
                    TextInput::make('alamat_pegawai')
                        ->autocapitalize('words')
                        ->label('Alamat Pegawai')
                        ->required()
                        ->placeholder('Masukkan alamat Pegawai')
                    ,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pegawai'),
                TextColumn::make('nama_pegawai'),
                TextColumn::make('no_telpon_pegawai'), 
                TextColumn::make('alamat_pegawai'),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}