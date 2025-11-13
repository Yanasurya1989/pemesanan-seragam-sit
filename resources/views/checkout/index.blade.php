@extends('layouts.app')

@section('content')
    <h2>Checkout Seragam</h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Pemesan</label>
            <input type="text" name="nama_pemesan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kelas</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <h5>Pilih Seragam</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr>
                        <td><input type="checkbox" name="product_id[]" value="{{ $p->id }}"></td>
                        <td><img src="{{ Storage::url($p->gambar) }}" width="70"></td>
                        <td>{{ $p->nama_seragam }}</td>
                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td><input type="number" name="qty[]" value="1" class="form-control" style="width:80px">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-success">Kirim Pesanan</button>
    </form>
@endsection
