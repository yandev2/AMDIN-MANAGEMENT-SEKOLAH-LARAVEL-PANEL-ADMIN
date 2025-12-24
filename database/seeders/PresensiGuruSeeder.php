<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiGuruSeeder extends Seeder
{
    public function run()
    {
        $guru_ids = [1770, 1771, 1772];
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2025, 11, 29);

        $locations = [
            ['latitude' => '-7.797068', 'longitude' => '110.370529'], 
            ['latitude' => '-7.798500', 'longitude' => '110.372000'],
            ['latitude' => '-7.796000', 'longitude' => '110.369000'],
        ];

        $absensiData = [];
        $currentDate = $startDate->copy();

        // Pilihan status kehadiran acak
        $absenOptions = ['H', 'I', 'S'];

        while ($currentDate <= $endDate) {
            foreach ($guru_ids as $index => $guru_id) {

                // Acak status absen
                $absenMasuk = $absenOptions[array_rand($absenOptions)];
                $absenKeluar = $absenMasuk;

                // Kalau hadir, buat jam dan durasi
                if ($absenMasuk === 'H') {
                    $jamMasuk = Carbon::parse('07:00')->addMinutes(rand(0, 120));
                    $jamKeluar = $jamMasuk->copy()->addHours(rand(7, 9))->addMinutes(rand(0, 30));
                    $durasi = $jamMasuk->diff($jamKeluar)->format('%H:%I:%S');
                    $status = $jamMasuk->hour > 8 ? 'terlambat' : 'tepat waktu';
                } else {
                    // Tidak hadir, kosongkan jam dan durasi
                    $jamMasuk = null;
                    $jamKeluar = null;
                    $durasi = null;
                    $status = 'Izin';
                }

                $absensiData[] = [
                    'id_sekolah' => 1,
                    'id_guru' => $guru_id,
                    'tanggal' => $currentDate->format('Y-m-d'),
                    'jam_masuk' => $jamMasuk ? $jamMasuk->format('H:i:s') : null,
                    'jam_keluar' => $jamKeluar ? $jamKeluar->format('H:i:s') : null,
                    'durasi_kerja' => $durasi,
                    'absen_masuk' => $absenMasuk,
                    'absen_keluar' => $absenKeluar,
                    'lokasi_masuk' => $locations[$index]['latitude'] . ',' . $locations[$index]['longitude'],
                    'lokasi_keluar' => $locations[$index]['latitude'] . ',' . $locations[$index]['longitude'],
                    'dokumen' => null,
                    'face' => null,
                    'keterangan' => null,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $currentDate->addDay();
        }

        DB::table('presensi_gurus')->insert($absensiData);
        $this->command->info('Data presensi guru berhasil diinsert untuk periode 1 Okt 2025 - 29 Nov 2025.');
    }
}
