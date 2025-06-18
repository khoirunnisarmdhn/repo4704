<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'Suppliers'; // Nama tabel (case-sensitive di beberapa DBMS)

    protected $primaryKey = 'Kode_supplier'; // Nama primary key
    public $incrementing = false; // Karena primary key bukan auto-increment
    protected $keyType = 'string'; // Karena Kode_supplier bertipe varchar/string

    protected $fillable = [
        'Kode_supplier',
        'Nama_supplier',
        'Alamat_supplier',
        'Barang',
        'created_at',
        'updated_at',
    ];

    // ✅ Hapus relasi yang tidak diperlukan jika tidak punya tabel relasi antar supplier
    // atau ganti relasi sesuai kebutuhan (misal: supplier memiliki banyak pembelian)
    // Contoh relasi yang lebih masuk akal:
    public function pembelianBahanBakus()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'kode_supplier', 'Kode_supplier');
    }

    // ✅ Tambahkan method utilitas untuk generate kode supplier otomatis
    public static function generateKodeSupplier()
    {
        $latest = self::latest('Kode_supplier')->first();
        $lastNumber = $latest ? (int) substr($latest->Kode_supplier, 1) : 0;
        $newNumber = $lastNumber + 1;

        return 'P' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // P0001, dst.
    }
}
