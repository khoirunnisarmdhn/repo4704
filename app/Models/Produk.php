<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getKodeProduk()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_produk), 'AB000') as kode_produk 
                FROM produk ";
        $kodeproduk = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeproduk as $kdprd) {
            $kd = $kdprd->kode_produk;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'AB'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }

    // Dengan mutator ini, setiap kali data harga_barang dikirim ke database, koma akan otomatis dihapus.
    public function setHargaProdukAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['harga_produk'] = str_replace(',', '', $value);
    }
}