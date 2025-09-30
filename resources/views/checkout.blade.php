<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
@include('partials.navbar')
<div class="container">
    <h3>Checkout Pesanan</h3>
       @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cartSession) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Album</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartSession as $id => $cs)
                    <tr>
                        <td><img src="{{ asset('storage/' . $cs['image_path']) }}" width="80"></td>
                        <td>{{ $cs['nama_produk'] }}</td>
                        <td>Rp {{ number_format($cs['harga'], 0, ',', '.') }}</td>
                        <td>{{ $cs['quantity'] }}</td>
                        <td>Rp {{ number_format($cs['harga'] * $cs['quantity'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-3">
            <button class="btn btn-success btn-block" id="pay-button">Bayar Dengan Midtrans</button>
        </div>
    @else
        <p>Tidak ada pesanan</p>
    @endif
</div>
</body>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
     document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Tangani jika pembayaran berhasil
            },
            onPending: function(result) {
                // Tangani jika pembayaran pending
            },
            onError: function(result) {
                // Tangani jika pembayaran gagal
            }
        });
    };
</script>
</html>
