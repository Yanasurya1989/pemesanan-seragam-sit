<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Product;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $data = BarangMasuk::with('product')->latest()->get();
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
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'harga_beli' => 'required|integer|min:0',
        ]);

        $total = $request->qty * $request->harga_beli;

        // simpan transaksi barang masuk
        BarangMasuk::create([
            'tanggal' => $request->tanggal,
            'nama_supplier' => $request->nama_supplier,
            'product_id' => $request->product_id,
            'size' => $request->size,
            'qty' => $request->qty,
            'harga_beli' => $request->harga_beli,
            'total_bayar' => $total,
        ]);

        // update stok produk
        $product = Product::find($request->product_id);
        $product->stok += $request->qty;
        $product->save();

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil ditambahkan & stok bertambah!');
    }
}
