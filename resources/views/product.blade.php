@include('partials.head')
<body>
    @include('partials.navbar')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $album->image_path) }}" alt="{{ $album->nama_produk }}" style="width: 100%; max-width: 500px;">
        </div>
        <div class="col-md-6">
            <h3>{{ $album->nama_produk }}</h3>
            <h4 class="text-muted mt-2">Rp {{ number_format($album->harga, 0, ',', '.') }}</h4>
            <form action="{{route('addcart',$album->id)}}" method="post">
            @csrf
            <input type="number" name="jumlah" value="1" min="1" placeholder="Input Jumlah" required>
            <button type="submit" class="btn btn-success">Tambah ke keranjang</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
