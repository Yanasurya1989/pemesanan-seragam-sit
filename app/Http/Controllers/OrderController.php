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
}
