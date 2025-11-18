@extends('layouts.app')

@section('content')
    <h3>Data Barang Masuk</h3>

    <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary mb-3">+ Barang Masuk</a>

    <table class="table table-bordered">
        <tr>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Barang</th>
            <th>Qty</th>
            <th>Harga Beli</th>
            <th>Total Bayar</th>
        </tr>

        @foreach ($data as $d)
            @foreach ($d->items as $item)
                <tr>
                    <td>{{ $d->tanggal }}</td>
                    <td>{{ $d->nama_supplier }}</td>
                    <td>{{ $item->product->nama_seragam ?? '—' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach

    </table>
@endsection
