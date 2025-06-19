<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Filament\Resources\JurnalResource\RelationManagers;
use App\Models\Jurnal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// tambahan
use App\Models\Coa;
use App\Models\JurnalDetail;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Columns\TextColumn;

use Filament\Forms\Components\Section;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // tambahan buat label Jurnal Umum
    protected static ?string $navigationLabel = 'Jurnal Umum';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Deskripsi Jurnal')
                ->schema([
                    DatePicker::make('tgl')
                        ->label('Tanggal')
                        ->required()
                        ->default(now()),

                    // Dropdown Nama Akun
                    Select::make('nama_akun')
                        ->label('Nama Akun')
                        ->options(Coa::all()->pluck('nama_akun', 'nama_akun'))
                        ->searchable()
                        ->required()
                        ->reactive() // <- Penting agar event-nya jalan
                        ->afterStateUpdated(function ($state, callable $set) {
                            $coa = Coa::where('nama_akun', $state)->first();
                            if ($coa) {
                                $set('kode_akun', $coa->kode_akun);
                            } else {
                                $set('kode_akun', null);
                            }
                        }),

                    // Dropdown Kode Akun yang diisi otomatis
                    Select::make('kode_akun')
                        ->label('Kode Akun')
                        ->options(Coa::all()->pluck('kode_akun', 'kode_akun'))
                        ->disabled() // Supaya user tidak ubah manual
                        ->dehydrated(true)
                        ->required(),

                    Textarea::make('deskripsi')->label('Deskripsi'),
                ])
                ->columns(1)
                ->collapsed()
                ->collapsible(),


            Section::make('Detail Jurnal')
                ->schema([
                    Repeater::make('jurnaldetail')
                        ->label('Detail Jurnal')
                        ->relationship()
                        ->schema([
                            Textarea::make('deskripsi')->label('Keterangan')->rows(2),
                            TextInput::make('debit')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('credit')
                                ->numeric()
                                ->default(0)
                                ->prefix('Rp')
                                ->required(),
                        ])
                        ->columns(1)
                        ->required(),
                ])
                ->collapsed()
                ->collapsible(),
        ])
        ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('tgl')->date(),
                 TextColumn::make('nama_akun')->label('Nama Akun'),
                 TextColumn::make('kode_akun')->label('Kode Akun'),
                 TextColumn::make('deskripsi')->limit(30),
                 TextColumn::make('jurnaldetail.debit')
                    ->label('Total Debit')
                    ->formatStateUsing(function ($state, $record) {
                        // Menghitung jumlah debit dari relasi jurnaldetail
                        // dd(var_dump($record));  // Debugging untuk melihat data relasi
                        $debit = $record->jurnaldetail()->sum('debit'); 
                        return rupiah($debit);
                    })
                    ->alignment('end') // Rata kanan
                , 
                TextColumn::make('jurnaldetail.credit')
                    ->label('Total Kredit')
                    ->formatStateUsing(function ($state, $record) {
                        $credit = $record->jurnaldetail()->sum('credit'); 
                        return rupiah($credit);
                    })
                    ->alignment('end') // Rata kanan
                , 
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
            ])
            ->defaultSort('tgl', 'desc')
            ;
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
            'index' => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit' => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}