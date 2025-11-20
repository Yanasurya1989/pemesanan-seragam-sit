<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_seragam' => 'required|string|max:255',
            'size' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'catatan_kecil' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_seragam', 'size', 'harga', 'harga_beli', 'stok', 'catatan_kecil']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
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
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'catatan_kecil' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama_seragam', 'size', 'harga', 'harga_beli', 'stok', 'catatan_kecil']);

        if ($request->hasFile('gambar')) {
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
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function frontend(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {
            $query->where('nama_seragam', 'like', "%{$search}%")
                ->orWhere('size', 'like', "%{$search}%");
        })->latest()->get();

        return view('frontend.products', compact('products'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $data = Excel::toArray([], $request->file('file'));

        if (count($data) == 0) {
            return back()->with('error', 'File Excel kosong!');
        }

        $rows = $data[0];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // skip header
            if (!isset($row[0])) continue;

            // Ambil nilai
            $nama = $row[0] ?? '';
            $size = $row[1] ?? '';
            $harga = is_numeric($row[2]) ? $row[2] : 0;
            $harga_beli = is_numeric($row[3]) ? $row[3] : 0;

            $stokRaw = $row[4] ?? 0;
            $stok = is_numeric($stokRaw) ? (int)$stokRaw : 0;

            $catatan = $row[5] ?? null;

            // Nama file gambar dari Excel
            $gambarNama = trim($row[6] ?? '');

            // Default null
            $gambarPath = null;

            // ============================================
            // CEK GAMBAR DI DUA LOKASI:
            // storage/app/public/products/
            // public/products/
            // ============================================
            if ($gambarNama !== '') {

                // Lokasi di storage
                $pathStorage = 'products/' . $gambarNama;

                // Lokasi di public
                $pathPublic = public_path('products/' . $gambarNama);

                if (Storage::disk('public')->exists($pathStorage)) {
                    // File ditemukan di storage
                    $gambarPath = $pathStorage;
                } elseif (file_exists($pathPublic)) {
                    // File ditemukan di public
                    $gambarPath = 'products/' . $gambarNama;
                }
            }

            // SIMPAN DATABASE
            Product::create([
                'nama_seragam'  => $nama,
                'size'          => $size,
                'harga'         => $harga,
                'harga_beli'    => $harga_beli,
                'stok'          => $stok,
                'catatan_kecil' => $catatan,
                'gambar'        => $gambarPath,
            ]);
        }

        return back()->with('success', 'Import berhasil!');
    }
}
