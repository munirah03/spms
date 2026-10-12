<?php

namespace App\Http\Controllers;

class QrCodeController extends Controller
{
    public function show()
    {
        // Set ORDER_PUBLIC_URL in .env when using a tunnel or domain.
        $url = config('app.order_url') ?: route('orders.create');

        return view('qrcode', ['url' => $url]);
    }
}
