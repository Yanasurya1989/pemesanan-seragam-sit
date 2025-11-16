<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $orders = Order::with('items.product')->latest();

        if ($this->request->from) {
            $orders->whereDate('created_at', '>=', $this->request->from);
        }

        if ($this->request->to) {
            $orders->whereDate('created_at', '<=', $this->request->to);
        }

        if ($this->request->nama) {
            $orders->where('nama_pemesan', 'like', '%' . $this->request->nama . '%');
        }

        return $orders->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Order',
            'Nama Pemesan',
            'Kelas',
            'No HP',
            'Alamat',
            'Produk',
            'Qty',
            'Total Harga',
            'Status Pembayaran',
            'Status Penerimaan',
        ];
    }

    public function map($order): array
    {
        // Gabungkan semua produk + qty
        $produk_list = [];
        foreach ($order->items as $item) {
            $produk_list[] = $item->product->nama_seragam . " ({$item->qty}x)";
        }

        return [
            $order->created_at->locale('id')->translatedFormat('d/m/Y H:i'),
            $order->nama_pemesan,
            $order->kelas,
            $order->no_hp,
            $order->alamat,
            implode(", ", $produk_list),
            $order->items->sum('qty'),
            "Rp " . number_format($order->total_harga, 0, ',', '.'),
            $order->is_paid ? 'Lunas' : 'Belum',
            $order->is_received ? 'Diterima' : 'Belum',
        ];
    }
}
