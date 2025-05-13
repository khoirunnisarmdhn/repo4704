<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; // Sesuaikan dengan nama tabel
    protected $primaryKey = 'id_pegawai'; // Sesuaikan dengan primary key yang ada
    public $incrementing = false; // Karena id_pegawai bertipe VARCHAR (bukan auto-increment)
    protected $keyType = 'string'; // Karena id_pegawai bertipe varchar

    protected static ?string $navigationGroup = 'Masterdata';

    // Tambahkan ini
    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'alamat_pegawai',
        'no_telpon_pegawai',
        'created_at',
        'updated_at'
    ];
}
