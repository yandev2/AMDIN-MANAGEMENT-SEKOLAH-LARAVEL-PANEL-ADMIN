<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\Gurus\GuruResource;
use App\Models\Guru;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuruAnalytic extends StatsOverviewWidget
{

    protected ?string $heading = 'Guru Analytic';


    public function getColumns(): array|int|null
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 5,
        ];
    }

    public function getStats(): array
    {
        $query = Guru::query();
        $jumlahGuru = $query->whereHas('jabatan', fn($q) => $q == null ?: $q->whereNot('nama_jabatan', null))->count();
        $dinasLuar =  Guru::where('status_dinas', 'dinas luar')->count();
        $dinasDalam =   Guru::where('status_dinas', 'dinas dalam')->count();
        $active =   Guru::where('status', 'active')->count();
        $inactive =   Guru::where('status', 'inactive')->count();

        $datas = [
            [
                "icon" => 'heroicon-o-users',
                "key" => 'guru',
                "value" => $jumlahGuru,
                "keys_quey" => null,
                "query" => null,
                "description" => 'jumlah keseluruhan guru',
                "attr" => 'guru',
            ],
            [
                "icon" => 'heroicon-o-arrow-right-start-on-rectangle',
                "key" => 'dinas luar',
                "value" => $dinasLuar,
                "keys_quey" => "status_dinas",
                "query" => 'dinas luar',
                "description" => 'guru dengan status dinas luar',
                "attr" => 'dinas_luar',
            ],
            [
                "icon" => 'heroicon-o-arrow-left-end-on-rectangle',
                "key" => 'dinas dalam',
                "value" => $dinasDalam,
                "keys_quey" => "status_dinas",
                "query" => 'dinas dalam',
                "description" => 'guru dengan status dinas dalam',
                "attr" => 'dinas_dalam',
            ],
            [
                "icon" => 'heroicon-o-check-circle',
                "key" => 'active',
                "value" => $active,
                "keys_quey" => "status",
                "query" => 'active',
                "description" => 'guru dengan status aktif',
                "attr" => 'active',
            ],
            [
                "icon" => 'heroicon-o-x-circle',
                "key" => 'inactive',
                "value" => $inactive,
                "keys_quey" => "status",
                "query" => 'inactive',
                "description" => 'guru dengan status tidak aktif',
                "attr" => 'inactive',
            ]
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
                ->url($d['keys_quey'] == null ? GuruResource::getUrl('index') : GuruResource::getUrl() . '?' . http_build_query([
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
