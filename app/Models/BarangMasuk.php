<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_supplier',
        'total_transaksi',
    ];

    public function items()
    {
        return $this->hasMany(BarangMasukItem::class);
    }
}
