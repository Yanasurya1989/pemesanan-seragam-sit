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
            'kelas' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'product_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        $order = Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'total_harga' => 0,
            'is_paid' => false,
            'is_received' => false,
        ]);

        $total = 0;

        foreach ($request->product_id as $i => $productId) {
            $product = Product::findOrFail($productId);
            $qty = (int)$request->qty[$i];

            // 🔒 Cek stok dulu
            if ($product->stok < $qty) {
                return back()->with('error', "Stok {$product->nama_seragam} tidak mencukupi! (tersisa {$product->stok})");
            }

            $subtotal = $product->harga * $qty;
            $total += $subtotal;

            // 🧾 Simpan item pesanan
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'harga_satuan' => $product->harga,
                'subtotal' => $subtotal,
            ]);

            // 📉 Kurangi stok
            $product->decrement('stok', $qty);
        }

        // 💰 Update total harga
        $order->update(['total_harga' => $total]);

        return redirect()->route('checkout.success', ['order' => $order->id])
            ->with('success', 'Pesanan berhasil dibuat!');
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
