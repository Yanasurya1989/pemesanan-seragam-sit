@extends('layouts.app')

@section('content')
    <h2>Daftar Seragam</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Tambah Seragam</a>

    {{-- Form Import Excel --}}
    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="d-flex align-items-center gap-2">
            <input type="file" name="file" class="form-control" style="max-width: 300px" required>
            <button class="btn btn-primary">Import Excel</button>

            {{-- Download Template --}}
            <a href="{{ asset('template_import_produk.xlsx') }}" class="btn btn-success">
                Download Template
            </a>
        </div>
    </form>

    {{-- Tabel Produk --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Size</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Catatan</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $p)
                <tr>
                    <td>{{ $p->nama_seragam }}</td>
                    <td>{{ $p->size }}</td>
                    <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>

                    <td>
                        @if ($p->stok > 0)
                            <span class="badge bg-success">{{ $p->stok }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>

                    <td>
                        @if ($p->catatan_kecil)
                            <small class="text-muted">{{ $p->catatan_kecil }}</small>
                        @else
                            <small>-</small>
                        @endif
                    </td>

                    <td>
                        @if ($p->gambar)
                            <img src="{{ asset('storage/' . $p->gambar) }}" width="120">
                        @else
                            <small>Tidak ada</small>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-sm">Beli Sekarang</a>
@endsection
