<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;

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

    public function addToCart($id, Request $request)
    {
        $album = Products::findOrFail($id);

        $jumlah = (int) $request->input('jumlah', 1);
        if ($jumlah < 1) $jumlah = 1;

        $cartSession = session()->get('cart',[]);

        if (isset($cartSession[$id])) {
            $cartSession[$id]['quantity']+= $jumlah;
        } else {
            $cartSession[$id] = [
                'nama_produk' => $album->nama_produk,
                'harga' => $album->harga,
                'quantity' => $jumlah
            ];
        }

        session()->put('cart',$cartSession);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan ke keranjang'
        ]);
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();

        $cartItems = $request->input('cart', []);

        if (empty($cartItems)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang kosong, tidak bisa checkout.'
            ], 400);
        }

        // Hitung total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => Str::uuid(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => implode(' ', array_slice(explode(' ', $user->name), 1)),
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan Snap Token',
                'error' => $e->getMessage(),
            ], 500);
        }
}

}
