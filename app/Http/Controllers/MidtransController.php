<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use App\Models\Midtrans as midtransModel;
use Illuminate\Http\Request;
use App\Service\createSnapToken;
use App\Service\Midtrans;

class MidtransController extends Controller
{
    protected $createSnapToken;
    public function __construct(createSnapToken $createSnapToken)
    {
        $this->createSnapToken = $createSnapToken;
    }

    public function __invoke(Request $request)
    {
        try {
            $harga = Spp::where('tahun', $request->input('tahun'))->value('nominal');
            $snap = $this->createSnapToken->getSnapToken($harga, $request->input('bulan'), auth()->user());

            MidtransModel::updateOrCreate(
                [
                    'siswa_id' => auth()->user()->siswa->id,
                    'nisn' => auth()->user()->siswa->nisn,
                    'bulan_bayar' => $request->input('bulan'),
                    'tahun_bayar' => $request->input('tahun')
                ],
                [
                    'order_id' => $snap[1]
                ]
            );

            return response()->json([
                'data' => $snap[0]
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th
            ], 500);
        }
    }
}
