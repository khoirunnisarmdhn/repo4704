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
        Schema::create('pembelian_bahanbaku', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->string('kode_supplier'); // Sesuaikan dengan tipe di supplier
            $table->foreign('kode_supplier')
                ->references('Kode_supplier') // Cocokkan dengan nama kolom di supplier
                ->on('suppliers')
                ->onDelete('cascade');
            $table->enum('status', ['pending', 'selesai'])->default('pending'); // Status pembelian
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahanbaku');
    }
};