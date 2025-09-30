<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
<div class="container">
    <h3>Registrasi</h3>
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{route('postregis')}}" method="post">
    @csrf
    <label for="">Nama</label>
    <input type="text" name="name" id="" class="form-control" placeholder="Masukkan namamu disini">
    <label for=""></label>
    <label for="">Email</label>
    <input type="email" name="email" class="form-control" id="" placeholder="Emailmu biar gampang verifikasi">
    <label for="">Password</label>
    <input type="password" name="password" id="" class="form-control" placeholder="mesti rahasia dan kamu aja yang tau,minimal 8 karakter">
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
