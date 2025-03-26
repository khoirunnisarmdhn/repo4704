<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPegawai extends Model
{
    use HasFactory;
    protected $table = 'form_pegawais'; // Nama tabel eksplisit

    protected $guarded = [];
}