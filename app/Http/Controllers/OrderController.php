<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items.product')->latest();

        if ($request->from) {
            $orders->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $orders->whereDate('created_at', '<=', $request->to);
        }

        $orders = $orders->get();

        return view('orders.index', compact('orders'));
    }

    public function togglePaid(Order $order)
    {
        $order->is_paid = !$order->is_paid;
        $order->paid_at = $order->is_paid ? now() : null;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Status pembayaran diperbarui!');
    }

    public function toggleReceived(Order $order)
    {
        $order->is_received = !$order->is_received;
        $order->received_at = $order->is_received ? now() : null;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Status penerimaan diperbarui!');
    }

    public function destroy(Order $order)
    {
        foreach ($order->items as $item) {
            $item->product->increment('stok', $item->qty);
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus dan stok dikembalikan.');
    }

    public function export(Request $request)
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }
}
