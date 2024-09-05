<?php

namespace App\Service;

// use App\Service\Midtrans;
use Midtrans\Snap;
use Midtrans\Config;

class createSnapToken
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_ID');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken($amount, $bulan, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . rand(100, 999),
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id' => 1,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'SPP - ' . $bulan,
                    "merchant_name" => "Walisongo SPP"
                ],
            ],
            'customer_details' => [
                'first_name' => $user->siswa->nama_siswa,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return [$snapToken, $params['transaction_details']['order_id']];
    }
}
