<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\AnalyticKehadiranGuruHariIni;
use App\Filament\User\Widgets\AnalyticKehadiranSiswaHariIni;
use App\Filament\User\Widgets\GuruAnalytic;
use App\Filament\User\Widgets\KehadiranGuruAnalytic;
use App\Filament\User\Widgets\KehadiranSiswaAnalytic;
use App\Filament\User\Widgets\SiswaAnalytic;
use App\Filament\User\Widgets\TabelDaftarIzinGuru;
use App\Filament\User\Widgets\TabelDaftarIzinSiswa;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{


    public function getHeaderWidgets(): array
    {
        return [
            GuruAnalytic::class,
            SiswaAnalytic::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            "sm"=>1,
            "md"=>1,
            "lg"=>2,
            "xl"=>2
        ];
    }
    public function getWidgets(): array
    {
        return [
            AnalyticKehadiranGuruHariIni::class,
            AnalyticKehadiranSiswaHariIni::class,

        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            TabelDaftarIzinGuru::class,
            TabelDaftarIzinSiswa::class,
            KehadiranGuruAnalytic::class,
            KehadiranSiswaAnalytic::class,
        ];
    }
}
