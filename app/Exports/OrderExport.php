<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Request;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $query = Order::with('items.product')->orderBy('created_at', 'DESC');

        if (request('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        return $query->get()->map(function ($order) {
            return [
                'Tanggal' => $order->created_at->format('d/m/Y H:i'),
                'Nama Pemesan' => $order->nama_pemesan,
                'Kelas' => $order->kelas,
                'No HP' => $order->no_hp,
                'Alamat' => $order->alamat,
                'Produk' => $order->items->map(fn($i) => $i->product->nama_seragam . " ({$i->qty}x)")->implode(', '),
                'Total Harga' => $order->total_harga,
                'Pembayaran' => $order->is_paid ? 'Lunas' : 'Belum',
                'Penerimaan' => $order->is_received ? 'Sudah' : 'Belum',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Pemesan',
            'Kelas',
            'No HP',
            'Alamat',
            'Produk',
            'Total Harga',
            'Status Pembayaran',
            'Status Penerimaan',
        ];
    }
}
