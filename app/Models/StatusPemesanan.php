<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPemesanan extends Model
{
    use HasFactory;

    protected $table = 'status_pemesanan';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'id_penjualan',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke tabel penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
}
