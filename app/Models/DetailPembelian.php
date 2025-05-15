<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';
    protected $fillable = ['id_detail_beli', 'id_pembelian', 'kode_produk', 'harga_satuan', 'jumlah', 'subtotal'];

    public function BahanBaku()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'id_pembelian');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk');
    }
}