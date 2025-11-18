<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukItem extends Model
{
    protected $fillable = [
        'barang_masuk_id',
        'product_id',
        'qty',
        'harga_beli',
        'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
