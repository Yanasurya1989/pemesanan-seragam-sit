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
            $table->decimal('total_transaksi', 12, 2)->default(0); // total dari detail
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_masuks');
    }
};
