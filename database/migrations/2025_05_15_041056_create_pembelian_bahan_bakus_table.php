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
            $table->foreignId('kode_supplier')->constrained('supplier')->onDelete('cascade');
            $table->datetime('tgl_pembelian'); 
            $table->decimal('total', 15, 2);
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