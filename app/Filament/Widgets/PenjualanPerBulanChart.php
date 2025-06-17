<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Penjualan Per Bulan ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        $orders = DB::table('penjualan')
            ->join('detail_penjualan', 'penjualan.id', '=', 'detail_penjualan.id_penjualan')
            ->where('penjualan.status', 'bayar') // Penjualan yang sudah dibayar
            ->whereYear('penjualan.tgl_penjualan', $year)
            ->selectRaw('MONTH(penjualan.tgl_penjualan) as month, SUM(detail_penjualan.harga_satuan * detail_penjualan.jumlah) as total_penjualan')
            ->groupBy('month')
            ->pluck('total_penjualan', 'month');

        $allMonths = collect(range(1, 12));

        $data = $allMonths->map(fn($month) => $orders->get($month, 0));

        $labels = $allMonths->map(
            fn($month) =>
            Carbon::create()->month($month)->locale('id')->translatedFormat('F')
        );

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
