<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanPerPelangganChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan per Pelanggan';

    protected function getData(): array
    {
        $data = DB::table('penjualan')
            ->join('pelanggan', 'penjualan.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('pelanggan.nama_pelanggan, SUM(penjualan.total) as total')
            ->groupBy('pelanggan.nama_pelanggan')
            ->orderByDesc('total')
            ->limit(10) // Tampilkan top 10 pelanggan
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_pelanggan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // atau 'pie' jika ingin pie juga
    }
}
