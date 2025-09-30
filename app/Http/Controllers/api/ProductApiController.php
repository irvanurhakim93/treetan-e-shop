<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Products::all();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
