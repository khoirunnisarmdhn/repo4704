<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Supplier extends Model
{
    use HasFactory;

    protected $table = 'Suppliers'; // Sesuaikan dengan nama tabel
    protected $primaryKey = 'Kode_supplier'; // Sesuaikan dengan primary key yang ada
    public $incrementing = false; // Karena id_pegawai bertipe VARCHAR (bukan auto-increment)
    protected $keyType = 'string'; // Karena id_pegawai bertipe varchar

    // Tambahkan ini
    protected $fillable = [
        'Kode_supplier',
        'Nama_supplier',
        'Alamat_supplier',
        'Barang',
        'created_at',
        'updated_at',
    ];

    public function supplier()
{
    return $this->belongsTo(Supplier::class, 'kode_supplier', 'Kode_supplier');
}


}