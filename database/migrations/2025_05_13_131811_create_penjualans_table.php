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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id_penjualan'); 
            $table->foreignId('id_pelanggan')->constrained('pelanggan')->onDelete('cascade');
            $table->string('no_faktur'); 
            $table->string('status'); 
            $table->datetime('tgl'); 
            $table->decimal('tagihan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};