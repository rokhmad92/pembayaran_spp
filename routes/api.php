<?php

use App\Models\Midtrans;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Service\notifWa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('midtrans/callback', function (Request $request, notifWa $notifWa) {
    if ($request->input('transaction_status') == 'settlement') {
        $midtrans = Midtrans::where('order_id', $request->input('order_id'))->first();
        Pembayaran::create([
            'kode_pembayaran' => $request->input('transaction_id'),
            'petugas_id' => 1,
            'siswa_id' => $midtrans->siswa_id,
            'nisn' => $midtrans->nisn,
            'tanggal_pembayaran' => $request->input('transaction_time'),
            'bulan_bayar' => $midtrans->bulan_bayar,
            'tahun_bayar' => $midtrans->tahun_bayar,
            'jumlah_bayar' => $request->input('gross_amount'),
        ]);

        // send notification whatsApp
        $siswa = Siswa::find($midtrans->siswa_id);
        $notifWa->sendWa($siswa['no_telepon'], $siswa['nama_siswa'], $midtrans->bulan_bayar);
    }

    return response()->json([
        'message' => 'Success get data from midtrans'
    ], 200);
});
