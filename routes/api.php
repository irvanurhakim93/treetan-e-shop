<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\OrderApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthApiController::class,'login']);
Route::post('/logout', [AuthApiController::class,'logout']);

Route::post('/webhook/midtrans', [HomeController::class, 'handleMidtransWebhook']);


Route::middleware('verify.access.key')->group(function () {
       Route::get('/products', [ProductController::class, 'index']);
       Route::post('/checkout', [OrderApiController::class, 'checkout']);
});
