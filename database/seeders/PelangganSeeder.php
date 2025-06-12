<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $i) {
            Pelanggan::create([
                'id_pelanggan' => Str::uuid(),
                'nama_pelanggan' => 'Pelanggan ' . $i,
                'alamat_pelanggan' => 'Alamat ' . $i,
                'no_telpon_pelanggan' => '08' . rand(1000000000, 9999999999),
            ]);
        }
    }
}
