<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PengingatPembayaran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pengingat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pengingat Pembayaran SPP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $namaBulan = Carbon::now()->format('F');

            $siswa = Siswa::all();

            foreach ($siswa as $item) {
                // pesan
                $message = "Salam " . $item->nama_siswa . ",\n";
                $message .= "\n";
                $message .= "Silahkan melakukan pembayaran SPP bulan " . $namaBulan . "\n";
                $message .= "\n";
                $message .= "Terima Kasih";

                Http::withHeaders(['Authorization' => env('FONNTE_API')])
                    ->post('https://api.fonnte.com/send', [
                        'target' => $item->no_telepon,
                        'message' => $message,
                    ]);

                Log::info("Berhasil kirim pengingat");
            }
        } catch (\Throwable $th) {
            Log::error("Error Pencairan: " . $th);
        }
    }
}
