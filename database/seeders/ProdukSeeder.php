<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkList = [
            ['nama' => 'Mukena Katun Jepang Motif Bunga', 'jenis' => 'Mukena Katun', 'harga' => 150000],
            ['nama' => 'Mukena Rayon Premium Polos', 'jenis' => 'Mukena Rayon', 'harga' => 175000],
            ['nama' => 'Mukena Silk Sutra Bordir Elegan', 'jenis' => 'Mukena Sutra', 'harga' => 250000],
            ['nama' => 'Mukena Parasut Traveling Ringan', 'jenis' => 'Mukena Parasut', 'harga' => 120000],
            ['nama' => 'Mukena Bali Warna Pastel', 'jenis' => 'Mukena Bali', 'harga' => 135000],
            ['nama' => 'Mukena Katun Rempel Anak', 'jenis' => 'Mukena Anak', 'harga' => 95000],
            ['nama' => 'Mukena Bordir Mewah Set Ransel', 'jenis' => 'Mukena Set', 'harga' => 270000],
            ['nama' => 'Mukena Couple Ibu Anak', 'jenis' => 'Mukena Couple', 'harga' => 320000],
            ['nama' => 'Mukena Katun Putih Sederhana', 'jenis' => 'Mukena Katun', 'harga' => 140000],
            ['nama' => 'Mukena Satin Embos Eksklusif', 'jenis' => 'Mukena Satin', 'harga' => 230000],
            ['nama' => 'Mukena Anak Karakter Hello Kitty', 'jenis' => 'Mukena Anak', 'harga' => 110000],
            ['nama' => 'Mukena Travel Set Tas Cantik', 'jenis' => 'Mukena Travel', 'harga' => 160000],
            ['nama' => 'Mukena Spandek Lembut Premium', 'jenis' => 'Mukena Spandek', 'harga' => 180000],
            ['nama' => 'Mukena Organza Transparan Layer', 'jenis' => 'Mukena Organza', 'harga' => 290000],
            ['nama' => 'Mukena Eksklusif Bordir Handmade', 'jenis' => 'Mukena Handmade', 'harga' => 350000],
        ];

        foreach ($produkList as $p) {
            Produk::create([
                'kode_produk' => Produk::getKodeProduk(),
                'nama_produk' => $p['nama'],
                'jenis_produk' => $p['jenis'],
                'harga_produk' => $p['harga'],
            ]);
        }
    }
}
