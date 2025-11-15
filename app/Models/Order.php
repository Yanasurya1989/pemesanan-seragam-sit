<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemesan',
        'kelas',
        'no_hp',
        'alamat',
        'total_harga',
        'is_paid',
        'is_received',
        'paid_at',
        'received_at',
    ];

    /**
     * Casting otomatis ke tipe data tertentu
     * Carbon instance untuk tanggal, boolean untuk status.
     */
    protected $casts = [
        'is_paid' => 'boolean',
        'is_received' => 'boolean',
        'paid_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    /**
     * Relasi ke item pesanan.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
