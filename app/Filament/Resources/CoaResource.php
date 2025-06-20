<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput; // Pastikan menggunakan namespace yang benar
use App\Filament\Resources\CoaResource\Pages;
use App\Filament\Resources\CoaResource\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('kode_akun')
                ->required()
                ->placeholder('Masukkan kode akun')
            ,
            TextInput::make('nama_akun')
                ->autocapitalize('words')
                ->label('Nama akun')
                ->required()
                ->placeholder('Masukkan nama akun')
            ,
            TextInput::make('header_akun')
                ->required()
                ->placeholder('Masukkan header akun')
            ,
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_akun')
                ->label('Kode Akun')
                ->sortable()
                ->searchable(),
                TextColumn::make('nama_akun')
                ->label('Nama Akun')
                ->sortable()
                ->searchable(),
                TextColumn::make('header_akun')
                ->label('Header Akun')
                ->sortable()
                ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kode_akun')
                    ->options([
                        1 => 'Aset/Aktiva',
                        2 => 'Utang',
                        3 => 'Modal',
                        4 => 'Pendapatan',
                        5 => 'Beban',
                    ]),
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}
