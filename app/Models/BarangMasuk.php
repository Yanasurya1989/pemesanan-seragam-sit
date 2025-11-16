<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_supplier',
        'product_id',
        'size',
        'qty',
        'harga_beli',
        'total_bayar',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
