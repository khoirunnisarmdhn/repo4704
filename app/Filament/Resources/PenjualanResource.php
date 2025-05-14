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

// DB
use Illuminate\Support\Facades\DB;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

        // merubah nama label menjadi Pelanggan
    protected static ?string $navigationLabel = 'Penjualan';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Wizard
                Wizard::make([
                    Wizard\Step::make('Pesanan')
                        ->schema([
                        // section 1
                            Forms\Components\Section::make('Faktur') // Bagian pertama
                                // ->description('Detail Barang')
                                ->icon('heroicon-m-document-duplicate')
                                ->schema([ 
                                    TextInput::make('no_faktur')
                                        ->default(fn () => Penjualan::getKodeFaktur()) // Ambil default dari method getKodeBarang
                                        ->label('Nomor Faktur')
                                        ->required()
                                        ->readonly() // Membuat field menjadi read-only
                                    ,
                                    DateTimePicker::make('tgl')->default(now()) // Nilai default: waktu sekarang
                                    ,
                                    Select::make('id_pelanggan')
                                        ->label('Pelanggan')
                                        ->options(Pelanggan::pluck('nama_pelanggan', 'id_pelanggan')->toArray()) // Mengambil data dari tabel
                                        ->required()
                                        ->placeholder('Pilih Pelanggan') // Placeholder default
                                    ,
                                    TextInput::make('tagihan')
                                        ->default(0) // Nilai default
                                        ->hidden()
                                    ,
                                    TextInput::make('status')
                                        ->default('pesan') // Nilai default status pemesanan adalah pesan/bayar/kirim
                                        ->hidden()
                                    ,
                                ])
                                ->collapsible() // Membuat section dapat di-collapse
                                ->columns(3)
                            ,
                        ]),
                    Wizard\Step::make('Pilih Barang')
                    ->schema([
                        // 
                            // untuk menambahkan repeater
                            Repeater::make('items')
                            ->relationship('DetailPenjualan')
                            // ->live()
                            ->schema([
                                Select::make('kode_produk')
                                        ->label('Produk')
                                        ->options(Produk::pluck('nama_produk', 'kode_produk')->toArray())
                                        // Mengambil data dari tabel
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems() //agar komponen item tidak berulang
                                        ->reactive() // Membuat field reactive
                                        ->placeholder('Pilih Produk') // Placeholder default
                                        ->afterStateUpdated(function ($state, $set) {
                                            $produk = Produk::find($state);
                                            $set('harga_satuan', $produk ? $produk->harga_produk : 0);
                                        })
                                        ->searchable()
                                ,
                                TextInput::make('harga_satuan')
                                    ->label('Harga Beli')
                                    ->numeric()
                                    ->default(fn ($get) => $get('kode_produk') ? Produk::find($get('kode_produk'))?->harga_produk ?? 0 : 0)
                                    ->readonly() // Agar pengguna tidak bisa mengedit
                                    ->hidden()
                                    ->dehydrated()
                                
                                ,
                                TextInput::make('jml')
                                    ->label('Jumlah')
                                    ->default(1)
                                    ->reactive()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        // $harga = $get('harga_jual'); // Ambil harga produk
                                        // $total = $harga * $state; // Hitung total
                                        // $set('total', $total); // Set total secara otomatis
                                        $subtotal = collect($get('DetailPenjualan'))
                                        ->sum(fn ($item) => ($item['harga_satuan'] ?? 0) * ($item['jumlah'] ?? 0));
                                        $set('jumlah', $jumlah);
                                    })
                                ,
                                DatePicker::make('tgl')
                                ->default(today()) // Nilai default: hari ini
                                ->required(),
                            ])
                            ->columns([
                                'md' => 4, //mengatur kolom menjadi 4
                            ])
                            ->addable()
                            ->deletable()
                            ->reorderable()
                            ->createItemButtonLabel('Tambah Item') // Tombol untuk menambah item baru
                            ->minItems(1) // Minimum item yang harus diisi
                            ->required() // Field repeater wajib diisi
                            ,

                            //tambahan form simpan sementara
                            // **Tombol Simpan Sementara**
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Simpan Sementara')
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

                                        // Simpan data produk
                                        foreach ($get('items') as $item) {
                                            DetailPenjualan::updateOrCreate(
                                                [
                                                    'id_penjualan' => $penjualan->id,
                                                    'kode_produk' => $item['kode_produk']
                                                ],
                                                [
                                                    'harga_beli' => $item['harga_beli'],
                                                    'harga_jual' => $item['harga_jual'],
                                                    'jml' => $item['jml'],
                                                    'tgl' => $item['tgl'],
                                                ]
                                            );

                                            // Kurangi stok produk di tabel produk
                                            $produk = produk::find($item['kode_produk']);
                                            if ($produk) {
                                                $produk->decrement('stok', $item['jml']); // Kurangi stok sesuai jumlah barang yang dibeli
                                            }
                                        }

                                        // Hitung total tagihan
                                        $totalTagihan = DetailPenjualan::where('id_penjualan', $penjualan->id)
                                            ->sum(DB::raw('harga_jual * jml'));

                                        // Update tagihan di tabel penjualan2
                                        $penjualan->update(['tagihan' => $totalTagihan]);
                                                                    })
                                        
                                        ->label('Proses')
                                        ->color('primary'),
                                                            
                                    ])    
       
                        // 
                    ])
                    ,
                    Wizard\Step::make('Pembayaran')
                        ->schema([
                            Placeholder::make('Tabel Pembayaran')
                                    ->content(fn (Get $get) => view('filament.components.penjualan-table', [
                                        'pembayarans' => Penjualan::where('no_faktur', $get('no_faktur'))->get()
                                ])), 
                        ]),
                ])->columnSpan(3)
                // Akhir Wizard
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                TextColumn::make('pelanggan.nama_pelanggan') // Relasi ke nama pelanggan 
                    ->label('Nama Pelanggan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bayar' => 'success',
                        'pesan' => 'warning',
                    }),
                TextColumn::make('tagihan')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    // ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                    ->sortable()
                    ->alignment('end') // Rata kanan
                ,
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
                    ->preload(), // Menampilkan semua opsi saat filter diklik
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}