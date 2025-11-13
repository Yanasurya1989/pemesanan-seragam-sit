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
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
