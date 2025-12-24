<?php

namespace App\Http\Controllers;

use App\Exports\GuruExportExel;
use App\Exports\PresensiGuruExportExel;
use App\Exports\PresensiSiswaExportExel;
use App\Exports\SiswaExportExel;
use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export_guru(Request $request)
    {
        $type = urldecode($request->type);
        $status_dinas = urldecode($request->status_dinas);

        $ids =  explode(',', urldecode($request->ids));
        $data = User::with(['guru', 'guru.jabatan', 'guru.shift'])->whereIn('id', $ids)->get();
        $guru = $data->toArray();

        $json = [];
        $dataGuru = [];
        $sekolah = Sekolah::findOrFail($guru[0]['id_sekolah'])->value('nama_sekolah');

        $data->each(function ($item) use (&$dataGuru) {
            $dataGuru[] = [
                'nama' => $item->name,
                'email' => $item->email,
                'nip' => $item->guru->nip,
                'jk' => $item->guru->jk,
                'alamat' => $item->guru->alamat,
                'no_hp' => $item->guru->no_hp,
                'agama' => $item->guru->agama,
                'pendidikan_terakhir' => $item->guru->pendidikan_terakhir,
                'tempat_lahir' => $item->guru->tempat_lahir,
                'tanggal_lahir' => $item->guru->tanggal_lahir,
                'shift' => $item->guru->shift->nama_shift,
                'jabatan' => $item->guru->jabatan->nama_jabatan,
            ];
        });


        $json = [
            'data_guru' => $dataGuru,
            'sekolah' => strtoupper($sekolah),
            'status_dinas' => $status_dinas == 'all' ? '' : $status_dinas
        ];


        $fileName = 'Export Data Guru tanggal ' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.guruExport', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');

            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new GuruExportExel($json), "{$fileName}.xlsx");
        }
    }

    public function export_presensi_guru(Request $request)
    {
        $type = urldecode($request->type);
        $ids =  explode(',', urldecode($request->ids));
        $data = PresensiGuru::with(['sekolah', 'guru'])->whereIn('id', $ids)->get();
        $grouped = $data->groupBy(fn($item) => $item->guru->id);
        $guru = $data->toArray();

        //=>hitung total semua kehadiran
        $status = ['H', 'I', 'S'];
        $total_kehadiran = collect($status)->mapWithKeys(function ($status) use ($guru) {
            return [$status => collect($guru)->where('absen_masuk', $status)->count()];
        });

        //=>ambil tahun ajaran yang sedang di export
        $tanggal = Carbon::parse($guru[0]['tanggal']);
        $tahun = $tanggal->year;
        $bulans = $tanggal->month;

        if ($bulans < 7) {
            $tahun_ajaran = ($tahun - 1) . '/' . $tahun;
        } else {
            $tahun_ajaran = $tahun . '/' . ($tahun + 1);
        }

        //=>ambil data kepala sekolah
        $kepsek = User::role('kepala_sekolah')->where('id_sekolah', $guru[0]['id_sekolah'])->first();

        //=>ambil data absen dan walikelas
        $data_absen = [];
        $grouped->each(function ($item) use (&$data_absen) {
            $data_absen[] = [
                "nama_guru" => $item[0]->guru->user->name,
                "nip" => $item[0]->guru->nip,
                "jk" => $item[0]->guru->jk,
                "absen_masuk" => $item->pluck('absen_masuk')->toArray(),
                "absen_keluar" => $item->pluck('absen_keluar')->toArray(),
                'tanggal' => $item->pluck('tanggal')->toArray(),
                'durasi' => $item->pluck('durasi_kerja')->toArray(),
                'keterangan' => $item->pluck('keterangan')->toArray(),
            ];
        });

        $json = [
            "bulan" => $tanggal,
            "tahun_ajaran" => $tahun_ajaran,
            "guru" => $data_absen,
            "nama_sekolah" =>  strtoupper($data[0]->sekolah->nama_sekolah),
            "total" => $total_kehadiran,
            "kepala_sekolah" => [
                "nama" => $kepsek->name,
                "nip" => $kepsek->guru->nip
            ],
        ];

        $fileName = 'Export Presensi Guru ' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.PresensiGuru', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new PresensiGuruExportExel($json), "{$fileName}.xlsx");
        }
    }

    public function export_siswa(Request $request)
    {
        $type = urldecode($request->type);
        $kelas = urldecode($request->kelas);

        $ids =  explode(',', urldecode($request->ids));
        $data = Siswa::with(['kelas', 'kelas.guru.user',])->whereIn('id', $ids)->get();
        $siswa = $data->toArray();

        $json = [];
        $dataSiswa = [];
        $sekolah = Sekolah::findOrFail($siswa[0]['id_sekolah'])->value('nama_sekolah');

        $data->each(function ($item) use (&$dataSiswa) {
            $dataSiswa[] = [
                'nis' => $item->nis,
                'nisn' => $item->nisn,
                'nama_siswa' => $item->nama_siswa,
                'jk' => $item->jk,
                'tempat_lahir' => $item->tempat_lahir,
                'tanggal_lahir' => $item->tanggal_lahir,
                'agama' => $item->agama,
                'alamat' => $item->alamat,
                'tahun_masuk' => $item->tahun_masuk,
                'foto' => $item->foto,
                'nik' => $item->nik,
                'no_kk' => $item->no_kk,
                'kelas' => $item->kelas == null ? 'Nan' : $item->kelas->nama_kelas,
            ];
        });

        $json = [
            "data_siswa" => $dataSiswa,
            "sekolah" => strtoupper($sekolah),
            "kelas" => $kelas == 'all' ? null : $kelas,
            "wali_kelas" =>  $kelas == 'all' ? null : $siswa[0]['kelas']['guru']['user']['name'],
            "nip_wali_kelas" =>  $kelas == 'all' ? null : $siswa[0]['kelas']['guru']['nip'],
        ];

        $fileName = 'Export Data Siswa ' . ($kelas == 'all' ? '' : 'Kelas ' . $kelas . ' tanggal ' . Carbon::now()->format('d-M-Y'));


        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.siswaExport', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');

            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new SiswaExportExel($json), "{$fileName}.xlsx");
        }
    }

    public function export_presensi_siswa(Request $request)
    {
        $type = urldecode($request->type);
        $ids =  explode(',', urldecode($request->ids));
        $data = PresensiSiswa::with(['siswa'])->whereIn('id', $ids)->get();
        $grouped = $data->groupBy(fn($item) => $item->siswa->id);
        $siswa = $data->toArray();

        $status = ['H', 'I', 'S', 'A'];
        $total_kehadiran = collect($status)->mapWithKeys(function ($status) use ($siswa) {
            return [$status => collect($siswa)->where('status', $status)->count()];
        });

        $tanggal = Carbon::parse($siswa[0]['tanggal']);
        $kelas = Kelas::where('id', $siswa[0]['siswa']['id_kelas'])->first();
        $tahun = $tanggal->year;
        $bulans = $tanggal->month;

        if ($bulans < 7) {
            $tahun_ajaran = ($tahun - 1) . '/' . $tahun;
        } else {
            $tahun_ajaran = $tahun . '/' . ($tahun + 1);
        }

        $kepsek = User::role('kepala_sekolah')->where('id_sekolah', $siswa[0]['id_sekolah'])->first();
        $data_absen = [];
        $grouped->each(function ($item) use (&$data_absen) {
            $data_absen[] = [
                "nama_siswa" => $item[0]->siswa->nama_siswa,
                "nis" => $item[0]->siswa->nis,
                "jk" => $item[0]->siswa->jk,
                "status" => $item->pluck('status')->toArray(),
                'tanggal' => $item->pluck('tanggal')->toArray(),
                'keterangan' => $item->pluck('keterangan')->toArray(),
            ];
        });

        $json = [
            "bulan" => $tanggal,
            "tahun_ajaran" => $tahun_ajaran,
            "siswa" => $data_absen,
            "nama_sekolah" =>  strtoupper($data[0]->sekolah->nama_sekolah),
            "total" => $total_kehadiran,
            "kelas" => [
                "wali_kelas" => $kelas->guru->user->name ?? 'Tidak ada wali kelas',
                "nip" => $kelas->guru->nip ?? 'Nan',
                "nama_kelas" => $kelas->nama_kelas,
            ],
            "kepala_sekolah" => [
                "nama" => $kepsek->name ?? 'Tidak ada kepala sekolah',
                "nip" => $kepsek->guru->nip ?? 'Nan'
            ],
        ];

        $fileName = 'Export Presensi Siswa Kelas '.$kelas->nama_kelas.' ' . Carbon::now()->format('d-M-Y');

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.PresensiSiswa', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename={$fileName}.pdf"
                ]
            );
        } else {
            return Excel::download(new PresensiSiswaExportExel($json), "{$fileName}.xlsx");
        }
    }
}
