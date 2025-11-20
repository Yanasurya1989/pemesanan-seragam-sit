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
            // 'alamat' => 'required|string',
            'product_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        $order = Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            // 'alamat' => $request->alamat,
            'alamat' => $request->alamat ?? null,
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

    public function addToCart(Request $request)
    {
        // $request->validate([
        //     'product_id' => 'required|integer',
        //     'qty' => 'required|integer|min=1'
        // ]);

        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1'
        ]);


        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        // Jika produk sudah ada, tambahkan qty
        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $request->qty;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'nama_seragam' => $product->nama_seragam,
                'harga' => $product->harga,
                'qty' => $request->qty,
                'size' => $product->size,
                'gambar' => $product->gambar,
                'stok' => $product->stok
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('checkout.cart', compact('cart'));
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->qty as $productId => $qty) {
            if (isset($cart[$productId])) {
                $cart[$productId]['qty'] = intval($qty);
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Keranjang diperbarui!');
    }

    public function checkoutCart(Request $request)
    {
        // Ubah data cart menjadi format yang sama seperti request checkout normal
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        $request->merge([
            'product_id' => array_column($cart, 'product_id'),
            'qty'        => array_column($cart, 'qty')
        ]);

        // Gunakan method store() yang sudah ada
        $response = $this->store($request);

        // Hapus cart jika sudah checkout
        session()->forget('cart');

        return $response;
    }
}
