@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">✅ Pesanan Berhasil!</h2>

        <div class="alert alert-success">
            <p>Terima kasih, <b>{{ $order->nama_pemesan }}</b>! Pesananmu telah kami terima.</p>
            <p>Total yang harus dibayar:
                <b>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</b>
            </p>
        </div>

        <h5>📦 Detail Pesanan:</h5>
        <ul>
            @foreach ($order->items as $item)
                <li>
                    {{ $item->product->nama_seragam }} (x{{ $item->qty }}) —
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </li>
            @endforeach
        </ul>

        <hr>

        <h5>💳 Silakan Transfer Pembayaran</h5>
        <p>Harap lakukan pembayaran ke salah satu rekening berikut:</p>
        <ul>
            <li><b>Bank BCA:</b> 1234567890 a.n. PT Qordova Fashion</li>
            <li><b>Bank Mandiri:</b> 9876543210 a.n. PT Qordova Fashion</li>
        </ul>

        <p>Atau gunakan <b>QRIS</b> berikut untuk pembayaran cepat:</p>
        <img src="{{ asset('images/qris.png') }}" alt="QRIS Pembayaran" width="200" class="mb-3">

        @php
            // Ubah ke nomor WA admin kamu (gunakan format internasional tanpa +)
            $adminPhone = '6289601353957';

            $waMessage =
                "Halo Admin, saya ingin konfirmasi pesanan:\n\n" .
                "Nama Pemesan: {$order->nama_pemesan}\n" .
                "No HP: {$order->no_hp}\n" .
                "Alamat: {$order->alamat}\n\n" .
                "Detail Pesanan:\n";

            foreach ($order->items as $item) {
                $waMessage .=
                    "- {$item->product->nama_seragam} (x{$item->qty}) = Rp " .
                    number_format($item->subtotal, 0, ',', '.') .
                    "\n";
            }

            $waMessage .=
                "\nTotal: Rp " .
                number_format($order->total_harga, 0, ',', '.') .
                "\n\nSudah saya transfer ke rekening / QRIS di atas. Mohon konfirmasinya ya 🙏";
            $waLink = "https://wa.me/{$adminPhone}?text=" . urlencode($waMessage);
        @endphp

        <div class="mt-4">
            <a href="{{ $waLink }}" target="_blank" class="btn btn-success">
                💬 Konfirmasi via WhatsApp
            </a>
            <a href="{{ route('checkout.index') }}" class="btn btn-secondary">
                🔙 Kembali ke katalog
            </a>
        </div>
    </div>
@endsection
