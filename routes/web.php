<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//sign up and authentication
Route::get('/', [AuthController::class,'index'])->name('login');
Route::post('/postlogin', [AuthController::class,'postlogin'])->name('postlogin');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');
Route::get('/registration', [AuthController::class,'registration'])->name('registration');
Route::post('/registration/post', [AuthController::class,'postRegis'])->name('postregis');


Route::get('/home', [HomeController::class,'index'])->name('home');
Route::get('album/{id}',[HomeController::class,'view'])->name('album.view');
Route::post('/add-to-cart/{id}',[HomeController::class,'addtoCart'])->name('addcart');
Route::get('/delete-cart/{id}', [HomeController::class,'deleteCart'])->name('deletecart');
Route::get('/carts', [HomeController::class,'cartPage'])->name('cartpage');
Route::get('/checkout', [HomeController::class,'checkoutPage'])->name('checkoutpage');
Route::post('delete-cart-after-transaction', [HomeController::class,'deleteCartAfterPay']);
