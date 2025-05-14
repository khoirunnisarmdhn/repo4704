<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku'; // Sesuaikan dengan nama tabel
    protected $primaryKey = 'Kode_bahanbaku'; // Sesuaikan dengan primary key yang ada
    public $incrementing = false; // Karena kode_bahanbaku bertipe VARCHAR (bukan auto-increment)
    protected $keyType = 'string'; // Karena kode_bahanbaku bertipe varchar

    // Tambahkan ini
    protected $fillable = [
        'Kode_bahanbaku',
        'Nama_bahanbaku',
        'Satuan',
        'Stok',
        'Harga_perunit',
        'Kode_supplier',
        'created_at',
        'updated_at'
    ];
}
