@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Daftar Pesanan Masuk</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Order</th>
                        <th>Nama Pemesan</th>
                        <th>Kelas</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Produk</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Status Penerimaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>

                            <td>{{ $order->nama_pemesan }}</td>
                            <td>{{ $order->kelas }}</td>
                            <td>{{ $order->no_hp }}</td>
                            <td>{{ $order->alamat }}</td>

                            <td>
                                @foreach ($order->items as $item)
                                    <div>
                                        <strong>{{ $item->product->nama_seragam }}</strong> ({{ $item->qty }}x)
                                    </div>
                                @endforeach
                            </td>

                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                            {{-- Status Pembayaran --}}
                            <td>
                                @if ($order->is_paid)
                                    <span class="badge bg-success">Lunas</span><br>
                                    <small class="text-muted">
                                        ({{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '-' }})
                                    </small>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>

                            {{-- Status Penerimaan --}}
                            <td>
                                @if ($order->is_received)
                                    <span class="badge bg-primary">Diterima</span><br>
                                    <small class="text-muted">
                                        ({{ $order->received_at ? $order->received_at->format('d/m/Y H:i') : '-' }})
                                    </small>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                {{-- Tombol toggle Lunas --}}
                                <form action="{{ route('orders.togglePaid', $order->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $order->is_paid ? 'btn-warning' : 'btn-success' }}">
                                        {{ $order->is_paid ? 'Tandai Belum Lunas' : 'Tandai Lunas' }}
                                    </button>
                                </form>

                                {{-- Tombol toggle Diterima --}}
                                <form action="{{ route('orders.toggleReceived', $order->id) }}" method="POST"
                                    class="d-inline mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $order->is_received ? 'btn-danger' : 'btn-info' }}">
                                        {{ $order->is_received ? 'Belum Diterima' : 'Sudah Diterima' }}
                                    </button>
                                </form>

                                {{-- Tombol WhatsApp --}}
                                @php
                                    $nomor_wa = preg_replace('/^0/', '62', $order->no_hp);
                                    $pesan = urlencode(
                                        "Halo {$order->nama_pemesan}, pesanan seragam Anda sudah siap diambil di sekolah. Terima kasih 🙏",
                                    );
                                    $link_wa = "https://wa.me/{$nomor_wa}?text={$pesan}";
                                @endphp

                                <a href="{{ $link_wa }}" target="_blank" class="btn btn-sm btn-success mt-1">
                                    <i class="bi bi-whatsapp"></i> Chat
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus pesanan ini? Stok akan dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger mt-1">🗑 Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
