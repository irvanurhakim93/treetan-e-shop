<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use App\Models\User;


class HomeController extends Controller
{

        public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $album = Products::all();
        return view('home',compact('album'));
    }

    public function view($id)
    {
        $album = Products::findOrFail($id);
        return view('product',compact('album'));
    }

    public function addtoCart($id, Request $request)
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
                'image_path' => $album->image_path,
                'quantity' => $jumlah
            ];
        }

        session()->put('cart',$cartSession);

        return redirect()->route('cartpage')->with('success','album berhasil dimasukkan ke keranjang');
    }

    public function deleteCart($id)
    {
         $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]); // Hapus item dengan ID tertentu
            session()->put('cart', $cart);
        }

    return redirect()->route('cartpage')->with('success', 'Album berhasil dihapus dari keranjang.');

    }

    public function cartPage()
    {
        $cartSession = session()->get('cart',[]);


        return view('cart',compact('cartSession'));
    }




    //checkout page untuk debug
    public function checkoutPage()
    {
    $userModel = auth()->user();
    $cartSession = session()->get('cart', []);

    if (empty($cartSession)) {
    return redirect()->route('cart.page')->withErrors(['error' => 'Keranjang kosong, tidak bisa checkout.']);
    }

    // Hitung total harga dari cart
    $total = 0;
    foreach ($cartSession as $item) {
        $total += $item['harga'] * $item['quantity'];
    }

    Config::$serverKey = 'SB-Mid-server-pVIDOE2oThrATWIFLoHf9KNQ'; // Ganti dengan key kamu
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // 🐞 Solusi 3: Debug config untuk memastikan config() berhasil mengambil dari .env
    logger()->info('Midtrans Config Check', [
        'server_key_from_config' => config('midtrans.server_key'),
        'is_production_from_config' => config('midtrans.is_production'),
    ]);

    // Buat parameter transaksi
    $params = [
        'transaction_details' => [
            'order_id' => Str::uuid(),
            'gross_amount' => $total,
        ],
    'customer_details' => [
    'first_name' => explode(' ', $userModel->name)[0],
    'last_name' => implode(' ', array_slice(explode(' ', $userModel->name), 1)),
    'email' => $userModel->email,
    ],
    ];

    // Ambil Snap Token dari Midtrans
    try {
        $snapToken = Snap::getSnapToken($params);
    } catch (\Exception $e) {
        // Tangani error 401 dengan lebih jelas
     dd('Snap Token Error:', $e->getMessage());
    }

    // Kirim data ke view checkout
    return view('checkout', compact('cartSession', 'snapToken'));

    }

    public function handleMidtransWebhook(Request $request)
{
    $json = $request->getContent();
    $data = json_decode($json, true);

    // Contoh data dari Midtrans
    $orderId = $data['order_id'] ?? null;
    $transactionStatus = $data['transaction_status'] ?? null;

    if (!$orderId || !$transactionStatus) {
        return response()->json(['message' => 'Invalid data'], 400);
    }

    // Cari pesanan berdasarkan order_id
    $order = Order::where('order_id', $orderId)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Update status berdasarkan transaction_status
    if ($transactionStatus === 'settlement') {
        $order->status = 'paid';
    } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
        $order->status = 'failed';
    }

    $order->save();

    return response()->json(['message' => 'Webhook received']);
}

}
