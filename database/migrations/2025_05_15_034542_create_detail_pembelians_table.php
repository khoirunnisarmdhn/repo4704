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
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian')
                ->constrained('pembelian_bahanbaku', 'id_pembelian')
                ->onDelete('cascade');
            $table->foreignId('kode_produk')
                ->constrained('produks')
                ->onDelete('cascade');
            $table->integer('harga_satuan');
            $table->integer('jml');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};
