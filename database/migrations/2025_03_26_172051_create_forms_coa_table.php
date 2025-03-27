<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsCoaTable extends Migration
{
    public function up()
    {
        Schema::create('forms_coa', function (Blueprint $table) {
            $table->string('kode_akun')->primary(); // Kode akun sebagai primary key
            $table->string('nama_akun');
            $table->string('header_akun');
            $table->string('kode_produk');
            $table->text('deskripsi'); // Deskripsi
            $table->string('dokumen'); // Path dokumen
            $table->string('gambar'); // Path gambar
            $table->boolean('is_admin')->default(false); // Admin status (0 = bukan admin, 1 = admin)
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('forms_coa');
    }
};
