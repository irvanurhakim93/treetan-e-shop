<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Treetan E-Shop</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="{{route('home')}}">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{(route('cartpage'))}}">Keranjang</a>
      </li>
            <li class="nav-item">
        <a class="nav-link" href="{{(route('cartpage'))}}">Riwayat Pemesanan    </a>
      </li>
    </ul>

     {{-- Kanan: Logout --}}
    <ul class="navbar-nav ms-auto ml-auto">
      @auth
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-link nav-link" style="display: inline; cursor:pointer;">Logout</button>
        </form>
      </li>
      @endauth
    </ul>

  </div>
</nav>
