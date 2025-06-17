<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkList = Produk::all();
        $pegawaiIds = Pegawai::pluck('id_pegawai')->toArray();
        $pelangganIds = Pelanggan::pluck('id_pelanggan')->toArray();

        foreach (range(1, 200) as $i) {
            // Ambil data acak untuk relasi
            $id_pegawai = $pegawaiIds[array_rand($pegawaiIds)];
            $id_pelanggan = $pelangganIds[array_rand($pelangganIds)];
            $tanggal = Carbon::now()->subDays(rand(0, 365));
            $status = ['pesan', 'bayar'][rand(0, 1)];

            // Buat penjualan kosong dulu
            $penjualan = Penjualan::create([
                'no_faktur' => 'FJ' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tgl_penjualan' => $tanggal,
                'status' => $status,
                'id_pegawai' => $id_pegawai,
                'id_pelanggan' => $id_pelanggan,
                'total' => 0, // dihitung setelah detail dibuat
            ]);

            $total = 0;

            // Tambahkan 1–5 detail produk
            foreach (range(1, rand(1, 5)) as $_) {
                $produk = $produkList->random();
                $jumlah = rand(1, 5);
                $harga = (int) $produk->harga_produk;

                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'kode_produk' => $produk->kode_produk,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                ]);

                $total += $jumlah * $harga;
            }

            // Update total penjualan
            $penjualan->update(['total' => $total]);
        }
    }
}
