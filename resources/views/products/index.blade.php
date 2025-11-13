@extends('layouts.app')

@section('content')
    <h2>Daftar Seragam</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Tambah Seragam</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Size</th>
                <th>Harga</th>
                <th>Stok</th>
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
                    <td>
                        @if ($p->stok > 0)
                            <span class="badge bg-success">{{ $p->stok }}</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>
                    <td>
                        @if ($p->gambar)
                            <img src="{{ Storage::url($p->gambar) }}" width="100" alt="Gambar">
                        @else
                            <small>Tidak ada</small>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-sm">Beli Sekarang</a>
@endsection
