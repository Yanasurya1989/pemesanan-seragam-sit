<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_supplier');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size');
            $table->integer('qty');
            $table->integer('harga_beli');
            $table->integer('total_bayar');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
