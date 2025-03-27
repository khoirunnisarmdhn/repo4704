<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formscoa extends Model
{
    use HasFactory;
    protected $table = 'forms_coa';
    protected $primaryKey = 'kode_akun'; // Gunakan kode_akun sebagai primary key
    protected $guarded = [];
}
