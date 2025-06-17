<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

// tambahan untuk cluster
use Phpml\Clustering\KMeans;
use App\Models\Penjualan2;
use App\Models\Pembeli2;

class ClusteringVisual extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.clustering-visual';

    // tambahan method untuk contoh clustering
    public function getViewData(): array
    {
        // Data statis untuk clustering
        // $samples = [
        //     ['name' => 'Point 1', 'x' => 1, 'y' => 2],
        //     ['name' => 'Point 2', 'x' => 2, 'y' => 1],
        //     ['name' => 'Point 3', 'x' => 1, 'y' => 1],
        //     ['name' => 'Point 4', 'x' => 8, 'y' => 9],
        //     ['name' => 'Point 5', 'x' => 9, 'y' => 8],
        //     ['name' => 'Point 6', 'x' => 8, 'y' => 8],
        //     ['name' => 'Point 7', 'x' => 5, 'y' => 5],
        //     ['name' => 'Point 8', 'x' => 6, 'y' => 5],
        //     ['name' => 'Point 9', 'x' => 5, 'y' => 6]
        // ];

        // Data dinamis untuk clustering
        $samples = Pembeli2::join('penjualan2', 'penjualan2.pembeli_id', '=', 'pembeli2.id')
            ->join('penjualan_barang2', 'penjualan2.id', '=', 'penjualan_barang2.penjualan_id')
            ->where('penjualan2.status', 'bayar')
            ->select('pembeli2.nama_pembeli as name', \DB::raw('SUM(penjualan2.tagihan) AS x'), \DB::raw('SUM(penjualan_barang2.jml) AS y'))
            ->groupBy('pembeli2.nama_pembeli')
            ->get()->toArray();

        // Ambil koordinat untuk clustering (x dan y saja)
        $coordinates = array_map(function($sample) {
            return [$sample['x'], $sample['y']];
        }, $samples);

        // Terapkan KMeans untuk 3 cluster
        $kmeans = new KMeans(3); 
        $clusters = $kmeans->cluster($coordinates);
        // dd($clusters);
        $dataPoints = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $point) {
                // Menemukan indeks titik pada array samples berdasarkan koordinat
                // Mencocokkan koordinat yang ada pada $cluster dengan $samples
                foreach ($samples as $pointIndex => $sample) {
                    if ($sample['x'] === $point[0] && $sample['y'] === $point[1]) {
                        $dataPoints[] = [
                            'x' => $sample['x'], // Koordinat x
                            'y' => $sample['y'], // Koordinat y
                            'name' => $sample['name'], // Nama titik
                            'cluster' => 'Cluster ' . ($clusterIndex + 1), // Nama cluster
                        ];
                        break;  // Stop setelah menemukan titik yang cocok
                    }
                }
            }
        }

        // Mengembalikan data untuk digunakan di view
        return [
            'dataPoints' => $dataPoints,
        ];
    }
}
