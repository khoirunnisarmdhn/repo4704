<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            Pegawai::create([
                'id_pegawai' => Str::uuid(),
                'nama_pegawai' => 'Pegawai ' . $i,
                'alamat_pegawai' => 'Alamat Pegawai ' . $i,
                'no_telpon_pegawai' => '08' . rand(1000000000, 9999999999),
            ]);
        }
    }
}
