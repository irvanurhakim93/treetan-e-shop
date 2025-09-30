<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
@include('partials.navbar')
<h3>Keranjang</h3>

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
                    <th>Aksi</th>
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
                        <td><a href="{{route('deletecart',$id)}} " class="btn btn-danger">Hapus</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-3">
            <a href="{{route('checkoutpage')}}" class="btn btn-success">Checkout</a>
        </div>
    @else
        <p>Keranjang kosong.</p>
    @endif

</body>
</html>
