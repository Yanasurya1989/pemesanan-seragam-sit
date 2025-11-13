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
                            <td>
                                @if ($order->is_paid)
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>
                            <td>
                                @if ($order->is_received)
                                    <span class="badge bg-primary">Diterima</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('orders.togglePaid', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $order->is_paid ? 'btn-warning' : 'btn-success' }}">
                                        {{ $order->is_paid ? 'Tandai Belum Lunas' : 'Tandai Lunas' }}
                                    </button>
                                </form>

                                <form action="{{ route('orders.toggleReceived', $order->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $order->is_received ? 'btn-danger' : 'btn-info' }}">
                                        {{ $order->is_received ? 'Belum Diterima' : 'Sudah Diterima' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
