@extends('layouts.app')

@section('content')
    <h2>
        Edit Seragam</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama_seragam" value="{{ old('nama_seragam', $product->nama_seragam) }}"
                class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Size</label>
            <input type="text" name="size" value="{{ old('size', $product->size) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gambar (biarkan kosong jika tidak ganti)</label><br>
            @if ($product->gambar)
                <img src="{{ Storage::url($product->gambar) }}" width="100" class="mb-2"><br>
            @endif
            <input type="file" name="gambar" class="form-control">
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
