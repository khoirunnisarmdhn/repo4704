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
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->string('nama_akun');
            $table->foreign('nama_akun')->references('nama_akun')->on('coa')->onDelete('cascade');
            $table->string('kode_akun');
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->onDelete('cascade');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal');
    }
};