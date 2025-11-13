<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    // public function create()
    // {
    //     return view('products.create');
    // }

    public function create($product_id)
    {
        $product = \App\Models\Product::findOrFail($product_id);
        return view('checkout.create', compact('product'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_seragam' => 'required|string|max:255',
            'size' => 'required|string|max:20',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_seragam', 'size', 'harga']);

        if ($request->hasFile('gambar')) {
            // menyimpan ke storage/app/public/products
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path; // simpan path relatif: products/xxxxx.jpg
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_seragam' => 'required|string|max:255',
            'size' => 'required|string|max:20',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_seragam', 'size', 'harga']);

        if ($request->hasFile('gambar')) {
            // hapus gambar lama bila ada
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // hapus file gambar jika ada
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function frontend()
    {
        $products = Product::latest()->get();
        return view('frontend.products', compact('products'));
    }
}
