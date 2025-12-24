<?php

namespace App\Filament\User\Widgets;

use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use App\Models\Shift;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticKehadiranSiswaHariIni extends ChartWidget
{
    protected ?string $heading = 'Analytic Kehadiran Siswa Hari Ini';

    public function getColumnSpan(): int|string|array
    {
        return [
            "sm" => 2,
            "md" => 2,
            "lg" => 1,
            "xl" => 1
        ];
    }

    public  function getMaxHeight(): ?string
    {
        return '50vh';
    }
    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $query = PresensiSiswa::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal', Carbon::today());


        if (!empty($activeFilter) && $activeFilter !== 'all') {
            $query->whereHas('siswa', function ($q) use ($activeFilter) {
                $q->where('id_kelas', $activeFilter);
            });
        }

        $data = $query
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'backgroundColor' => [
                        '#c90404',   // merah transparan
                        '#c9a504', // kuning transparan
                        '#04a2c9',
                        '#14c904',  // hijau transparan
                    ],

                    'data' => [$data['A'] ?? 0, $data['S'] ?? 0, $data['I'] ?? 0, $data['H'] ?? 0],
                ],

            ],
            'labels' => ['Alpa', 'Sakit', 'Izin', 'Hadir'],
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
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 17,
                        'boxHeight' => 17,
                        'font' => [
                            'size' => 12,
                        ],

                    ],
                ],
            ]
        ];
    }

    protected function getFilters(): ?array
    {
        $filters = Kelas::pluck('nama_kelas', 'id')->toArray();

        return ['all' => 'Semua'] + $filters;
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
