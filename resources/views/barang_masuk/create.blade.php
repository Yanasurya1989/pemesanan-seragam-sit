@extends('layouts.app')

@section('content')
    <h3>Input Barang Masuk</h3>

    <form method="POST" action="{{ route('barang-masuk.store') }}">
        @csrf

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control">
        </div>

        <h5>Pilih Barang</h5>

        <table class="table table-bordered">
            <tr>
                <th>Pilih</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Subtotal</th>
            </tr>

            @foreach ($products as $p)
                <tr id="row-{{ $loop->index }}">
                    <td>
                        <input type="checkbox" class="item-check" data-id="{{ $loop->index }}"
                            name="items[{{ $loop->index }}][checked]" value="1">
                    </td>

                    <td>{{ $p->nama_seragam }}</td>

                    <td>
                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $p->id }}">

                        <input type="number" class="form-control qty" data-id="{{ $loop->index }}"
                            name="items[{{ $loop->index }}][qty]" value="0" disabled>
                    </td>

                    <td>
                        <input type="number" class="form-control harga-beli" data-id="{{ $loop->index }}"
                            name="items[{{ $loop->index }}][harga_beli]" value="{{ $p->harga_beli ?? 0 }}" disabled>
                    </td>

                    <td>
                        <input type="number" class="form-control subtotal" data-id="{{ $loop->index }}" readonly
                            disabled>
                    </td>
                </tr>
            @endforeach
        </table>

        <h4>Total Transaksi: <span id="total">0</span></h4>

        <button class="btn btn-success mt-3">Simpan</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function hitungTotal() {
                let total = 0;

                document.querySelectorAll(".item-check:checked").forEach(chk => {
                    let id = chk.dataset.id;

                    let qty = parseInt(document.querySelector(`.qty[data-id='${id}']`).value) || 0;
                    let harga = parseInt(document.querySelector(`.harga-beli[data-id='${id}']`).value) || 0;

                    let subtotal = qty * harga;

                    document.querySelector(`.subtotal[data-id='${id}']`).value = subtotal;

                    total += subtotal;
                });

                document.getElementById("total").innerText = total;
            }

            // Checkbox aktif/nonaktif qty & harga
            document.querySelectorAll(".item-check").forEach(chk => {
                chk.addEventListener("change", function() {
                    let id = this.dataset.id;

                    let qtyInput = document.querySelector(`.qty[data-id='${id}']`);
                    let hargaInput = document.querySelector(`.harga-beli[data-id='${id}']`);
                    let subtotalInput = document.querySelector(`.subtotal[data-id='${id}']`);

                    let aktif = this.checked;

                    qtyInput.disabled = !aktif;
                    hargaInput.disabled = !aktif;
                    subtotalInput.disabled = !aktif;

                    if (!aktif) {
                        qtyInput.value = 0;
                        subtotalInput.value = 0;
                    }

                    hitungTotal();
                });
            });

            // Semua input qty & harga otomatis hitung ulang
            document.addEventListener("input", hitungTotal);
        });
    </script>
@endsection
