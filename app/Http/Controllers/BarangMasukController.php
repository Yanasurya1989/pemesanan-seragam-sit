<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Product;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $data = BarangMasuk::with('items.product')->get();
        return view('barang_masuk.index', compact('data'));
    }


    public function create()
    {
        $products = Product::all();
        return view('barang_masuk.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_supplier' => 'required|string',
            'items' => 'required|array',
        ]);

        $items = collect($request->items)
            ->filter(fn($i) => isset($i['checked']) && $i['checked'] == 1);

        if ($items->isEmpty()) {
            return back()->with('error', 'Pilih minimal satu barang.');
        }

        $total = 0;

        $header = BarangMasuk::create([
            'tanggal' => $request->tanggal,
            'nama_supplier' => $request->nama_supplier,
            'total_transaksi' => 0,
        ]);

        foreach ($request->items as $item) {

            if (!isset($item['checked'])) {
                continue;
            }

            $subtotal = $item['qty'] * $item['harga_beli'];
            $total += $subtotal;

            $header->items()->create([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'harga_beli' => $item['harga_beli'],
                'subtotal' => $subtotal,
            ]);

            $product = Product::find($item['product_id']);
            $product->stok += $item['qty'];
            $product->save();
        }

        $header->update(['total_transaksi' => $total]);

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil ditambahkan');
    }
}
