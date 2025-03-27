<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coa';
    protected $primaryKey = 'kode_akun'; // Gunakan kode_akun sebagai primary key
    public $incrementing = false; // Karena kode_akun bukan auto-increment
    protected $keyType = 'string'; // Karena kode_akun berupa string
    protected $guarded = [];
}
