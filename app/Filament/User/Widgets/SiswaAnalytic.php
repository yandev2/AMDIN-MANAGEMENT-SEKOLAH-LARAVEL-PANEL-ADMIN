<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\Gurus\GuruResource;
use App\Filament\User\Resources\Siswas\SiswaResource;
use App\Models\Guru;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiswaAnalytic extends StatsOverviewWidget
{

    protected ?string $heading = 'Siswa Analytic';


    public function getColumns(): array|int|null
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 4,
        ];
    }

    public function getStats(): array
    {
        $query = Siswa::query();
        $jumlahSiswa = $query->count();
        $lakiLaki =  $query->where('jk', 'L')->where('status', 'aktif')->count();
        $perempuan =    $query->where('jk', 'P')->count();
        $lulus =    $query->where('status', 'lulus')->count();

        $datas = [
            [
                "icon" => 'heroicon-o-users',
                "key" => 'siswa',
                "value" => $jumlahSiswa,
                "keys_quey" => 'status',
                "query" => 'aktif',
                "description" => 'jumlah siswa',
                "attr" => 'guru',
            ],
            [
                "icon" => 'heroicon-o-user-group',
                "key" => 'laki-laki',
                "value" => $lakiLaki,
                "keys_quey" => "jk",
                "query" => 'L',
                "description" => 'siswa laki laki',
                "attr" => 'active',
            ],
           [
                "icon" => 'heroicon-o-user-group',
                "key" => 'perempuan',
                "value" => $perempuan,
                "keys_quey" => "jk",
                "query" => 'P',
                "description" => 'siswa perempuan',
                "attr" => 'dinas_dalam',
            ],
           [
                "icon" => 'heroicon-o-user-group',
                "key" => 'siswa lulus',
                "value" => $lulus,
                "keys_quey" => "status",
                "query" => 'lulus',
                "description" => 'siswa yang lulus',
                "attr" => 'inactive',
            ],
          
        ];

        $stats = [];

        foreach ($datas as $d) {

            $stats[] =  Stat::make($d['key'], '')
                ->value($d['value'])
                ->description($d['description'])
                ->descriptionColor('success')
                ->icon($d['icon'])
                ->chart([10,10])
                ->extraAttributes(['class' => 'stats-'.$d['attr']])
                ->url($d['keys_quey'] == null ? SiswaResource::getUrl('index') : SiswaResource::getUrl() . '?' . http_build_query([
                    'filters' => [
                        $d['keys_quey'] => [
                            'value' => $d['query']
                        ],
                    ],
                ]));
        }
        return $stats;
    }
}
