<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
<div class="container">
    @if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
    @endif
    <h3>Hai, selamat datang di Music Store</h3>
    <p>Mari Login dulu sebelum bisa lihat - lihat</p>
    <form action="{{route('postlogin')}}" method="post">
    @csrf
    <label for="">Email</label>
    <input type="email" name="email" class="form-control" id="">
    <label for="">Password</label>
    <input type="password" name="password" id="" class="form-control">
    <br>
    <button type="submit" class="btn btn-success">Login</button>
    </form>
    <br>
    <br>
    <p>Belum punya akun ? sini <a href="{{route('registration')}}">Daftar</a> dulu</p>
</div>
</body>
</html>
