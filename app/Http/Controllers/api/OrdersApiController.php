<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersApiController extends Controller
{
        public function checkout(Request $request)
    {
        $user = auth()->user();

        $items = $request->input('items', []);
        $total = collect($items)->sum(function ($item) {
            return $item['harga'] * $item['quantity'];
        });

        if ($total <= 0) {
            return response()->json(['message' => 'Invalid total amount'], 400);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = Str::uuid();

        // Simpan order ke database
        $order = Order::create([
            'order_id' => $orderId,
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $total,
            'items' => json_encode($items)
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Midtrans error', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Checkout created',
            'snap_token' => $snapToken
        ]);
    }

    public function history()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }
}
