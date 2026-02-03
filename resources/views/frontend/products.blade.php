@extends('layouts.app')

@section('content')
    @php
        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'qty'));
    @endphp

    {{-- Cart Floating Info --}}
    <div class="mb-4 d-flex justify-content-end">
        <a href="{{ route('cart.index') }}" class="btn btn-warning position-relative">
            🛒 Keranjang

            @if ($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>

    <div class="container py-5">

        {{-- Header --}}
        <div class="text-center mb-5">b
            <h1 class="fw-bold">🛍️ Katalog Seragam SIT Qordovab</h1>
            <p class="text-muted">Temukan berbagai jenis seragam yang dibutuhkan.</p>
        </div>

        {{-- Search --}}
        <form action="{{ route('frontend.products') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Cari seragam atau ukuran...">
                <button class="btn btn-primary">Cari</button>
            </div>
        </form>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Produk Grid --}}
        <div class="row g-4">
            @forelse($products as $p)
                {{-- ❗ Fix Responsive: Mobile 1 card, Tablet 2, Desktop 3–4 --}}
                <div class="col-12 col-lg-6 col-xl-4 col-xxl-3">
                    <div class="card shadow-sm border-0 h-100">

                        {{-- Gambar Produk --}}
                        @if ($p->gambar)
                            <img src="{{ Storage::url($p->gambar) }}" class="card-img-top" alt="{{ $p->nama_seragam }}"
                                style="object-fit: cover; height: 220px;">
                        @else
                            <img src="{{ asset('images/no-image.jpg') }}" class="card-img-top" alt="No Image"
                                style="object-fit: cover; height: 220px;">
                        @endif

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title">{{ $p->nama_seragam }}</h5>
                            <p class="text-muted mb-1">Ukuran: {{ strtoupper($p->size) }}</p>
                            <p class="fw-bold text-success mb-1">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </p>

                            <p class="text-muted mb-3">Stok:
                                @if ($p->stok > 0)
                                    <span class="text-success fw-bold">{{ $p->stok }}</span>
                                @else
                                    <span class="text-danger fw-bold">Habis</span>
                                @endif
                            </p>

                            {{-- Tombol Pesan --}}
                            @if ($p->stok > 0)
                                <a href="{{ route('checkout.create', ['product_id' => $p->id]) }}"
                                    class="btn btn-primary mt-auto">
                                    🛒 Pesan Sekarang
                                </a>
                            @else
                                <button class="btn btn-secondary mt-auto" disabled>
                                    🚫 Stok Habis
                                </button>
                            @endif

                            {{-- Tambah ke Keranjang --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <input type="hidden" name="qty" value="1">

                                <button class="btn btn-success w-100">➕ Tambah ke Keranjang</button>
                            </form>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada produk tersedia.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
