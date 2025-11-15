@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Checkout Seragam</h2>

        <div class="card shadow-sm border-0 overflow-hidden">
            {{-- Versi Mobile: Gambar di atas --}}
            <div class="d-block d-md-none text-center bg-light p-3">
                <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_seragam }}"
                    class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
            </div>

            <div class="row g-0 align-items-center">
                {{-- Gambar (Desktop only) --}}
                <div class="col-md-5 d-none d-md-block bg-light text-center">
                    <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_seragam }}"
                        class="img-fluid rounded-start" style="max-height: 100%; object-fit: cover;">
                </div>

                {{-- Form --}}
                <div class="col-md-7 p-4">
                    <h4 class="mb-3">{{ $product->nama_seragam }}</h4>
                    <p class="mb-1">Ukuran: <strong>{{ $product->size }}</strong></p>
                    <p>Harga: <strong>Rp {{ number_format($product->harga, 0, ',', '.') }}</strong></p>

                    <form action="{{ route('checkout.store') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label>Jumlah (Qty)</label>
                            <input type="number" name="qty[]" class="form-control" min="1" value="1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Nama Pemesan</label>
                            <input type="text" name="nama_pemesan" class="form-control" required>
                        </div>

                        {{-- <div class="mb-3">
                            <label>Kelas</label>
                            <input type="text" name="kelas" class="form-control" required>
                        </div> --}}

                        <div class="mb-3">
                            <label>Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: 9A" required>
                        </div>

                        <div class="mb-3">
                            <label>No HP (untuk konfirmasi)</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Pesan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
