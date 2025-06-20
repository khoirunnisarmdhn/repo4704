<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahanbaku'; // nama tabel di database

    protected $primaryKey = 'id_pembelian'; // PK kustom

    public $incrementing = false; // karena ID bukan auto-increment (asumsi)
    protected $keyType = 'string'; // jika id_pembelian berupa kode (misal: PB-00001)

    protected $guarded = []; // semua kolom bisa diisi massal

    /**
     * Generate Kode Faktur Pembelian otomatis.
     */
    public static function getKodeFaktur()
    {
        $result = DB::selectOne("SELECT IFNULL(MAX(id_pembelian), 'PB-0000000') as id_pembelian FROM pembelian_bahanbaku");
        $lastNumber = substr($result->id_pembelian, -7);
        $newNumber = (int)$lastNumber + 1;
        return 'PB-' . str_pad($newNumber, 7, "0", STR_PAD_LEFT);
    }

    /**
     * Relasi ke tabel Supplier
     */
    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'kode_supplier', 'kode_supplier');
    }

    /**
     * Relasi ke detail pembelian (jika ada)
     */
    public function detailPembelian()
    {
        return $this->hasMany(Supplier::class, 'Nama_supplier', 'nama_supplier');
    }
}
