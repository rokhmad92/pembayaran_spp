<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class notifWa
{
    public function sendWa($nomor, $nama, $bulan): void
    {
        // pesan
        $message = "Salam " . $nama . ",\n";
        $message .= "\n";
        $message .= "Selamat pembayaran SPP bulan " . $bulan . " berhasil!!\n";
        $message .= "\n";
        $message .= "Terima Kasih";

        try {
            Http::withHeaders(['Authorization' => env('FONNTE_API')])
                ->post('https://api.fonnte.com/send', [
                    'target' => $nomor,
                    'message' => $message,
                ]);

            Log::info("Berhasil kirim pesan");
        } catch (\Throwable $th) {
            Log::info(json_encode($th));
        }
    }
}
