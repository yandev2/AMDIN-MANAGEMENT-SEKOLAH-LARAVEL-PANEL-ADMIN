<?php

namespace App\Filament\User\Widgets;

use App\Models\PresensiGuru;
use App\Models\Shift;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticKehadiranGuruHariIni extends ChartWidget
{
    protected ?string $heading = 'Analytic Kehadiran Guru Hari Ini';

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
        $query = PresensiGuru::query()
            ->select('absen_masuk', DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal', Carbon::today());

        if (!empty($activeFilter) && $activeFilter !== 'all') {
            $query->whereHas('guru', function ($q) use ($activeFilter) {
                $q->where('id_shift', $activeFilter);
            });
        }

        $data = $query
            ->groupBy('absen_masuk')
            ->pluck('total', 'absen_masuk');

        return [
            'datasets' => [
                [
                    'backgroundColor' => [
                        '#c90404',   // merah transparan
                        '#c9a504', // kuning transparan
                        '#14c904',  // hijau transparan
                    ],

                    'data' => [$data['S'] ?? 0, $data['I'] ?? 0, $data['H'] ?? 0],
                ],
            ],
            'labels' => ['Sakit', 'Izin', 'Hadir'],
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
        $filters = Shift::pluck('nama_shift', 'id')->toArray();

        return ['all' => 'Semua'] + $filters;
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
