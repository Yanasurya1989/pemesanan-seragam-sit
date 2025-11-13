@extends('layouts.app')

@section('content')
    <div class="container py-5">

        {{-- Header --}}
        <div class="text-center mb-5">
            <h1 class="fw-bold">🛍️ Katalog Seragam Sekolah</h1>
            <p class="text-muted">Temukan berbagai jenis seragam dengan kualitas terbaik dan harga terjangkau.</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Produk Grid --}}
        <div class="row g-4">
            @forelse($products as $p)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
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
                            <p class="fw-bold text-success mb-3">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>

                            <a href="{{ route('checkout.create', ['product_id' => $p->id]) }}"
                                class="btn btn-primary mt-auto">
                                🛒 Pesan Sekarang
                            </a>
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
