<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Wizard; //untuk menggunakan wizard
use Filament\Forms\Components\TextInput; //untuk penggunaan text input
use Filament\Forms\Components\DateTimePicker; //untuk penggunaan date time picker
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; //untuk penggunaan select
use Filament\Forms\Components\Repeater; //untuk penggunaan repeater
use Filament\Tables\Columns\TextColumn; //untuk tampilan tabel
use Filament\Forms\Components\Placeholder; //untuk menggunakan text holder
use Filament\Forms\Get; //menggunakan get 
use Filament\Forms\Set; //menggunakan set 
use Filament\Forms\Components\Hidden; //menggunakan hidden field
use Filament\Tables\Filters\SelectFilter; //untuk menambahkan filter

// model

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\DetailPenjualan;
use App\Models\Pembayaran;

// DB
use Illuminate\Support\Facades\DB;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Pesanan')->schema([
                    Forms\Components\Section::make('Faktur')
                        ->icon('heroicon-m-document-duplicate')
                        ->schema([
                            TextInput::make('no_faktur')
                                ->default(fn () => Penjualan::getKodeFaktur())
                                ->label('Nomor Faktur')
                                ->required()
                                ->readonly(),

                            DateTimePicker::make('tgl')->default(now()),

                            Select::make('id_pelanggan')
                                ->label('Pelanggan')
                                ->options(Pelanggan::pluck('nama_pelanggan', 'id_pelanggan')->toArray())
                                ->required()
                                ->placeholder('Pilih Pelanggan'),

                            TextInput::make('tagihan')->default(0)->hidden(),
                            TextInput::make('status')->default('pesan')->hidden(),
                        ])
                        ->collapsible()
                        ->columns(3),
                ]),

                Wizard\Step::make('Pilih Barang')->schema([
                    Repeater::make('items')
                        ->relationship('DetailPenjualan')
                        ->schema([
                            Select::make('kode_produk')
                                ->label('Produk')
                                ->options(Produk::pluck('nama_produk', 'kode_produk')->toArray())
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->reactive()
                                ->placeholder('Pilih Produk')
                                ->afterStateUpdated(function ($state, $set) {
                                    $produk = Produk::find($state);
                                    $set('harga_produk', $produk?->harga_produk ?? 0);
                                    $set('subtotal', 0);
                                }),

                            TextInput::make('harga_produk')
                                ->numeric()
                                ->dehydrated()
                                ->default(0),

                            TextInput::make('jml')
                                ->numeric()
                                ->default(1)
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $subtotal = ($get('harga_produk') ?? 0) * $state;
                                    $set('subtotal', $subtotal);
                                }),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(4)
                        ->addable()
                        ->deletable()
                        ->reorderable()
                        ->createItemButtonLabel('Tambah Item')
                        ->minItems(1)
                        ->required(),

                    Forms\Components\Actions::make([
                        Action::make('Simpan Sementara')
                            ->action(function ($get) {
                                $penjualan = Penjualan::updateOrCreate(
                                    ['no_faktur' => $get('no_faktur')],
                                    [
                                        'tgl' => $get('tgl'),
                                        'id_pelanggan' => $get('id_pelanggan'),
                                        'status' => 'pesan',
                                        'tagihan' => 0
                                    ]
                                );

                                foreach ($get('items') as $item) {
                                    DetailPenjualan::updateOrCreate(
                                        [
                                            'id_penjualan' => $penjualan->id,
                                            'kode_produk' => $item['kode_produk']
                                        ],
                                        [
                                            'harga_produk' => $item['harga_produk'],
                                            'jml' => $item['jml'],
                                            'subtotal' => $item['subtotal']
                                        ]
                                    );

                                    $produk = Produk::find($item['kode_produk']);
                                    if ($produk) {
                                        $produk->decrement('stok', $item['jml']);
                                    }
                                }

                                $totalTagihan = DetailPenjualan::where('id_penjualan', $penjualan->id)
                                    ->sum('subtotal');

                                $penjualan->update(['tagihan' => $totalTagihan]);
                            })
                            ->label('Proses')
                            ->color('primary'),
                    ])
                ]),

                Wizard\Step::make('Pembayaran')->schema([
                    DatePicker::make('tgl_pembayaran')
                        ->label('Tanggal Pembayaran')
                        ->default(today())
                        ->required(),

                    TextInput::make('jumlah_bayar')
                        ->label('Jumlah Bayar')
                        ->numeric()
                        ->required(),

                    Select::make('metode_pembayaran')
                        ->label('Metode Pembayaran')
                        ->options([
                            'tunai' => 'Tunai',
                            'transfer' => 'Transfer',
                            'qris' => 'QRIS',
                        ])
                        ->required(),

                    Forms\Components\Actions::make([
                        Action::make('Simpan Pembayaran')
                            ->action(function ($get) {
                                $penjualan = Penjualan::where('no_faktur', $get('no_faktur'))->first();

                                if ($penjualan) {
                                    Pembayaran::create([
                                        'id_penjualan' => $penjualan->id,
                                        'tgl_pembayaran' => $get('tgl_pembayaran'),
                                        'jumlah_bayar' => $get('jumlah_bayar'),
                                        'metode_pembayaran' => $get('metode_pembayaran'),
                                    ]);

                                    $penjualan->update(['status' => 'bayar']);
                                }
                            })
                            ->label('Simpan Pembayaran')
                            ->color('success')
                    ])
                ])
            ])->columnSpan(3)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                TextColumn::make('pelanggan.nama_pelanggan')->label('Nama Pelanggan')->sortable()->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bayar' => 'success',
                        'pesan' => 'warning',
                    }),
                TextColumn::make('tagihan')
                    ->formatStateUsing(fn ($state): string => rupiah($state))
                    ->sortable()
                    ->alignment('end'),
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pesan' => 'Pemesanan',
                        'bayar' => 'Pembayaran',
                    ])
                    ->searchable()
                    ->preload(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
