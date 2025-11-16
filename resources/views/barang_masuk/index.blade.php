@extends('layouts.app')

@section('content')
    <h3>Data Barang Masuk</h3>

    <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary mb-3">+ Barang Masuk</a>

    <table class="table table-bordered">
        <tr>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Barang</th>
            <th>Size</th>
            <th>Qty</th>
            <th>Harga Beli</th>
            <th>Total Bayar</th>
        </tr>

        @foreach ($data as $d)
            <tr>
                <td>{{ $d->tanggal }}</td>
                <td>{{ $d->nama_supplier }}</td>
                <td>{{ $d->product->nama_seragam }}</td>
                <td>{{ $d->size }}</td>
                <td>{{ $d->qty }}</td>
                <td>Rp {{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($d->total_bayar, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
@endsection
