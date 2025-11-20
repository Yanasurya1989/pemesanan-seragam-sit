@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <h2 class="fw-bold mb-4">🛒 Keranjang Belanja</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (count($cart) == 0)
            <div class="alert alert-info text-center">
                Keranjang masih kosong. <a href="{{ route('frontend.products') }}">Belanja sekarang</a>
            </div>
        @else
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf

                <table class="table table-bordered align-middle">
                    <thead>
                        <tr class="table-secondary">
                            <th>Produk</th>
                            <th>Harga</th>
                            <th width="120">Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $total = 0; @endphp

                        @foreach ($cart as $item)
                            @php $subtotal = $item['harga'] * $item['qty']; @endphp
                            @php $total += $subtotal; @endphp

                            <tr>
                                <td>
                                    <strong>{{ $item['nama_seragam'] }}</strong><br>
                                    Ukuran: {{ strtoupper($item['size']) }}<br>
                                    Stok: {{ $item['stok'] }}
                                </td>

                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>

                                <td>
                                    <input type="number" min="1" name="qty[{{ $item['product_id'] }}]"
                                        value="{{ $item['qty'] }}" class="form-control">
                                </td>

                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        <tr class="table-warning fw-bold">
                            <td colspan="3" class="text-end">Total</td>
                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-secondary mb-4">Update Keranjang</button>
            </form>

            {{-- Form Identitas --}}
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf

                <h4 class="mb-3">🧍‍♂️ Data Pemesan</h4>

                <div class="mb-3">
                    <label>Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Kelas</label>
                    <input type="text" name="kelas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>

                {{-- <div class="mb-3">
                    <label>Alamat (optional)</label>
                    <textarea name="alamat" class="form-control"></textarea>
                </div> --}}

                <button class="btn btn-primary btn-lg w-100">💳 Checkout Sekarang</button>
            </form>
        @endif

    </div>
@endsection
