@extends('layouts.app')

@section('content')
    <h3>Input Barang Masuk</h3>

    <form action="{{ route('barang-masuk.store') }}" method="POST">
        @csrf

        <div class="mb-2">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control">
        </div>

        <div class="mb-2">
            <label>Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control">
        </div>

        <div class="mb-2">
            <label>Nama Barang</label>
            <select name="product_id" class="form-control">
                @foreach ($products as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_seragam }} - {{ $p->size }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label>Size</label>
            <input type="text" name="size" class="form-control">
        </div>

        <div class="mb-2">
            <label>Qty</label>
            <input type="number" name="qty" class="form-control">
        </div>

        <div class="mb-2">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control">
        </div>

        <button class="btn btn-success mt-3">Simpan</button>
    </form>
@endsection
