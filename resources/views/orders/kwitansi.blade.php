<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 6px;
        }
    </style>
</head>

<body>

    <h3 class="header">Kwitansi Pembayaran</h3>

    <p><strong>No Pesanan:</strong> {{ $order->id }}</p>
    <p><strong>Nama Pemesan:</strong> {{ $order->nama_pemesan }}</p>
    <p><strong>Kelas:</strong> {{ $order->kelas }}</p>
    <p><strong>Tanggal Lunas:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}</p>

    <table>
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>

        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->nama_seragam }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->product->harga * $item->qty, 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="2">Total</th>
            <th>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</th>
        </tr>
    </table>

    <p style="margin-top: 20px;">
        Terima kasih telah melakukan pembelian di sekolah kami.
    </p>

</body>

</html>
