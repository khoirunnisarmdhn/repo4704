<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Phpml\Clustering\KMeans;
use Illuminate\Support\Facades\DB;

class ClusteringVisual extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.clustering-visual';

    public function getViewData(): array
    {
        // Ambil data pelanggan, total belanja (x), dan jumlah barang dibeli (y)
        $samples = DB::table('pelanggan')
            ->join('penjualan', 'penjualan.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->join('detail_penjualan', 'penjualan.id', '=', 'detail_penjualan.id_penjualan')
            ->where('penjualan.status', 'bayar') // status yang sudah bayar
            ->select(
                'pelanggan.nama_pelanggan as name',
                DB::raw('SUM(detail_penjualan.harga_satuan * detail_penjualan.jumlah) AS x'), // total belanja
                DB::raw('SUM(detail_penjualan.jumlah) AS y') // total barang
            )
            ->groupBy('pelanggan.nama_pelanggan')
            ->get()
            ->toArray();

        // Ubah data menjadi array numerik
        $coordinates = array_map(function ($sample) {
            return [(float) $sample->x, (float) $sample->y];
        }, $samples);

        // Terapkan KMeans untuk 3 cluster
        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($coordinates);

        // Gabungkan kembali dengan nama pelanggan
        $dataPoints = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $point) {
                foreach ($samples as $sample) {
                    if ((float) $sample->x === $point[0] && (float) $sample->y === $point[1]) {
                        $dataPoints[] = [
                            'x' => $sample->x,
                            'y' => $sample->y,
                            'name' => $sample->name,
                            'cluster' => 'Cluster ' . ($clusterIndex + 1),
                        ];
                        break;
                    }
                }
            }
        }

        return [
            'dataPoints' => $dataPoints,
        ];
    }
}
