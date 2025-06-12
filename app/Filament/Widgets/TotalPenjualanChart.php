<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan Produk';

    protected function getData(): array
    {
        // Ambil total penjualan produk berdasarkan nama produk
        $data = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->join('produks', 'detail_penjualan.kode_produk', '=', 'produks.kode_produk')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('produks.nama_produk, SUM(detail_penjualan.harga_satuan * detail_penjualan.jumlah) as total_penjualan')
            ->groupBy('produks.nama_produk')
            ->orderByDesc('total_penjualan')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_produk')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
