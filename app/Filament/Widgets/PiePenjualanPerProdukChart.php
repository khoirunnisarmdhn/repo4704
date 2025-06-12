<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PiePenjualanPerProdukChart extends ChartWidget
{
    protected static ?string $heading = 'Persentase Penjualan Tiap Produk';

    protected function getData(): array
    {
        $data = DB::table('detail_penjualan')
            ->join('produks', 'detail_penjualan.kode_produk', '=', 'produks.kode_produk')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('produks.nama_produk, SUM(detail_penjualan.harga_satuan * detail_penjualan.jumlah) as total')
            ->groupBy('produks.nama_produk')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_produk')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
