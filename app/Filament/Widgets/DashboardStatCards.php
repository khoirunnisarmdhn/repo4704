<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class DashboardStatCards extends BaseWidget
{
    protected function getStats(): array
    {
        // Total pelanggan
        $totalPelanggan = Pelanggan::count();

        // Total transaksi penjualan
        $totalTransaksi = Penjualan::count();

        // Total penjualan
        $totalPenjualan = Penjualan::sum('total');

        // Total keuntungan: dari detail_penjualan (harga_satuan - biaya_pokok) * jumlah
        $totalKeuntungan = DetailPenjualan::join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('SUM((detail_penjualan.harga_satuan - 0) * detail_penjualan.jumlah) as keuntungan') // Ganti 0 jika ada harga modal
            ->value('keuntungan');

        return [
            Stat::make('Total Pelanggan', $totalPelanggan)
                ->description('Jumlah pelanggan terdaftar'),

            Stat::make('Total Transaksi', $totalTransaksi)
                ->description('Jumlah transaksi penjualan'),

            Stat::make('Total Penjualan', rupiah($totalPenjualan))
                ->description('Total seluruh penjualan'),

            Stat::make('Total Keuntungan', rupiah($totalKeuntungan ?? 0))
                ->description('Estimasi margin keuntungan'),
        ];
    }
}
