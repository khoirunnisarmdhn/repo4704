<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnal'; // Nama tabel eksplisit

    protected $guarded = [];

    // relasi ke COA
    public function coa()
    {
    return $this->belongsTo(Coa::class, 'kode_akun');
    }

    // relasi ke jurnal detail
    public function jurnaldetail()
    {
        return $this->hasMany(JurnalDetail::class);
    }
    
    // Optional: cek apakah seimbang
    public function isBalanced()
    {
        $debit = $this->jurnaldetail->sum('debit');
        $credit = $this->jurnaldetail->sum('credit');
        return $debit == $credit;
    }
}
