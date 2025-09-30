<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function registration()
    {
        return view('registration');
    }

    public function postRegis(Request $request)
    {
        $validData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        ]);

        $usermodel = User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => bcrypt($validData['password'])
        ]);


        return redirect()->route('login')->with('success','Registrasi berhasil');


    }

    public function postlogin(Request $request)
    {
       $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);




    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('home')->with('success', 'Berhasil login!');
    }

    // Jika gagal
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->withInput();

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success','Anda telah Logout,jangan lupa mampir lagi kapan - kapan');
    }

}
