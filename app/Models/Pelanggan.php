<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; // Sesuaikan dengan nama tabel
    protected $primaryKey = 'id_pelanggan'; // Sesuaikan dengan primary key yang ada
    public $incrementing = false; // Karena id_pelanggan bertipe VARCHAR (bukan auto-increment)
    protected $keyType = 'string'; // Karena id_pelanggan bertipe varchar

    protected static ?string $navigationGroup = 'Masterdata';

    // Tambahkan ini
    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat_pelanggan',
        'no_telpon_pelanggan',
        'created_at',
        'updated_at'
    ];
}
