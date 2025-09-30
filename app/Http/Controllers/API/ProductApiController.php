<?php

namespace App\Http\Controllers;

use App\Models\Products;
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
