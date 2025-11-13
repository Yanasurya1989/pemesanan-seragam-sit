<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('checkout.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'product_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        $order = Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'total_harga' => 0,
        ]);

        $total = 0;

        foreach ($request->product_id as $i => $productId) {
            $product = Product::findOrFail($productId);
            $qty = (int)$request->qty[$i];
            $subtotal = $product->harga * $qty;
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'harga_satuan' => $product->harga,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_harga' => $total]);

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }

    public function create($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('checkout.create', compact('product'));
    }
}
