<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function togglePaid(Order $order)
    {
        $order->update(['is_paid' => !$order->is_paid]);
        return redirect()->route('orders.index')->with('success', 'Status pembayaran diperbarui!');
    }

    public function toggleReceived(Order $order)
    {
        $order->update(['is_received' => !$order->is_received]);
        return redirect()->route('orders.index')->with('success', 'Status penerimaan diperbarui!');
    }

    public function destroy(Order $order)
    {
        // Kembalikan stok produk
        foreach ($order->items as $item) {
            $item->product->increment('stok', $item->qty);
        }

        // Hapus pesanan
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus dan stok dikembalikan.');
    }
}
