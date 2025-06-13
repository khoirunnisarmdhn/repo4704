<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'Suppliers'; // Nama tabel
    protected $primaryKey = 'Kode_supplier'; // Primary key kustom
    public $incrementing = false; // Karena VARCHAR
    protected $keyType = 'string';

    protected $fillable = [
        'Kode_supplier',
        'Nama_supplier',
        'Alamat_supplier',
        'Barang',
        'created_at',
        'updated_at',
    ];

    /**
     * Generate kode supplier otomatis (misal: P00001)
     */
    // Event "creating" untuk generate Kode_supplier
    protected static function booted()
    {
        static::creating(function ($supplier) {
            // Menghasilkan kode acak yang diawali dengan 'BRG' dan diikuti dengan 4 digit angka
            $supplier->Kode_supplier = 'SUP' . rand(100000, 999999);
        });
    }

    public function pembelianBahanBaku()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'kode_supplier', 'Kode_supplier');
    }
}
