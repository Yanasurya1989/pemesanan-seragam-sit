<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_masuk_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('barang_masuk_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('qty')->default(1);
            $table->decimal('harga_beli', 12, 2);   // ambil otomatis dari product.harga_beli
            $table->decimal('subtotal', 12, 2);     // qty * harga_beli

            $table->timestamps();

            // RELASI
            $table->foreign('barang_masuk_id')
                ->references('id')->on('barang_masuks')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_masuk_items');
    }
};
