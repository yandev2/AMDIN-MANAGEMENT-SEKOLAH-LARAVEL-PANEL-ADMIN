<?php

namespace App\Filament\User\Widgets;

use App\Models\Jabatan;
use App\Models\PresensiGuru;
use App\Models\Shift;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class KehadiranGuruAnalytic extends ChartWidget
{

    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '250px';

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        $year = Carbon::now()->year;
        return 'Analytic Kehadiran Guru Tahun ' . $year;
    }

    public function getData(): array
    {
        $year = Carbon::now()->year;

        $labels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $activeFilter = $this->filter;
        // Ambil data dari tabel presensi (PostgreSQL style)
        $query = PresensiGuru::selectRaw('CAST(EXTRACT(MONTH FROM tanggal) AS INTEGER) as bulan, absen_masuk, COUNT(*) as total')
            ->whereRaw('EXTRACT(YEAR FROM tanggal) = ?', [$year])
            ->where('id_sekolah', auth()->user()->id_sekolah)
            ->groupByRaw('EXTRACT(MONTH FROM tanggal), absen_masuk');

        if (!empty($activeFilter) && $activeFilter !== 'all') {
            $query->whereHas('guru', function ($q) use ($activeFilter) {
                $q->where('id_shift', $activeFilter);
            });
        }
        $presensi = $query->get();

        // Status dan warna chart-nya
        $statuses = [
            'H' => ['label' => 'Hadir', 'color' =>'#14c904' ],
            'I' => ['label' => 'Izin', 'color' => '#c9a504' ],
            'S' => ['label' => 'Sakit', 'color' =>'#c90404' ],
        ];

        // Inisialisasi data kosong 12 bulan
        $data = [];
        foreach ($statuses as $kode => $info) {
            $data[$info['label']] = array_fill(1, 12, 0);
        }

        // Isi data dari hasil query
        foreach ($presensi as $p) {
            $bulan = (int) $p->bulan;
            $statusKey = strtoupper($p->absen_masuk);
            $statusInfo = $statuses[$statusKey] ?? null;

            if ($statusInfo) {
                $data[$statusInfo['label']][$bulan] = $p->total;
            }
        }

        // Siapkan dataset untuk Chart.js
        $datasets = collect($data)->map(function ($values, $label) use ($statuses) {
            // Ambil warna sesuai label
            $color = collect($statuses)->firstWhere('label', $label)['color'] ?? '#888888';
            return [
                'label' => $label,
                'data' => array_values($values),
                'backgroundColor' => $color,
                'borderWidth' => 0,
                'borderColor' => $color


            ];
        })->values()->toArray();

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'animation' => [
                'duration' => 1000, // durasi animasi (ms)
                'easing' => 'easeOutQuart', // gaya animasi
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'boxWidth' => 17,
                        'boxHeight' => 17,
                        'font' => [
                            'size' => 12,
                        ],

                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],

        ];
    }

    protected function getFilters(): ?array
    {
        $filters = Shift::pluck('nama_shift', 'id')->toArray();

        return ['all' => 'Semua'] + $filters;
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
