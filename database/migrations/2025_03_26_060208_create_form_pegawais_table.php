<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_pegawais', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama'); // Nama
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // Jenis kelamin
            $table->text('deskripsi'); // Deskripsi
            $table->string('kategori'); // Kategori
            $table->date('tanggal_lahir'); // Tanggal lahir
            $table->string('gambar'); // Path gambar
            $table->string('dokumen'); // Path dokumen
            $table->boolean('is_admin')->default(false); // Admin status (0 = bukan admin, 1 = admin)
            $table->text('content'); //Content 
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_pegawais');
    }
};
