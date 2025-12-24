<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiSiswaSeeder extends Seeder
{
    public function run()
    {
        $siswaIds = [1, 45, 46, 47, 48, 49, 50, 51, 52, 53];
        $statusOptions = ['H', 'S', 'I', 'A'];
        $tahunAjaran = '2025/2026';
        $idSekolah = 1;

        $startDate = Carbon::create(2025, 10, 1);
        $endDate = Carbon::create(2025, 11, 30);

        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        $data = [];

        foreach ($dates as $tanggal) {
            foreach ($siswaIds as $idSiswa) {
                $status = $statusOptions[array_rand($statusOptions)];
                $keterangan = in_array($status, ['S', 'I', 'A']) ? 'Keterangan ' . $status : null;

                $data[] = [
                    'id_sekolah' => $idSekolah,
                    'id_siswa' => $idSiswa,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'tahun_ajaran' => $tahunAjaran,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'bulan'=> $tanggal->format('Y-m')
                ];
            }
        }

        DB::table('presensi_siswas')->insert($data);
    }
}
