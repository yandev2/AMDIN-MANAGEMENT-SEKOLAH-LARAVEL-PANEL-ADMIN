<?php

namespace App\Filament\User\Resources\PresensiSiswas\Pages;

use App\Filament\User\Resources\PresensiSiswas\PresensiSiswaResource;
use App\Models\Kelas;
use App\Models\PresensiSiswa;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Alignment;

class ListPresensiSiswas extends ListRecords
{
    protected static string $resource = PresensiSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('Export Presensi')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->modalIcon('heroicon-o-arrow-down-on-square-stack')
                ->modalWidth('md')
                ->color('success')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Export Presensi Siswa')
                ->modalDescription('export data presensi siswa dalam bentuk pdf atau exel')
                ->modalWidth('md')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('type')
                                ->label('Tipe File')
                                ->native(false)
                                ->required()
                                ->columnSpan(1)
                                ->placeholder('')
                                ->options([
                                    'exel' => 'Exel',
                                    'pdf' => 'Pdf'
                                ]),
                            DatePicker::make('bulan')
                                ->required()
                                ->native(false)
                                ->displayFormat('F Y')
                                ->format('Y-m')
                                ->closeOnDateSelection()
                                ->label('Pilih Bulan dan Tahun'),
                            Select::make('kelas')
                                ->required()
                                ->native(false)
                                ->preload()
                                ->columnSpanFull()
                                ->placeholder('')
                                ->options( Kelas::pluck('nama_kelas', 'id'))

                        ])
                ])
                ->action(function ($data) {
                    $bulanTahun = $data['bulan']; // "2025-10"
                    [$tahun, $bulan] = explode('-', $bulanTahun);
                    $ids = PresensiSiswa::query()
                        ->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan)
                        ->whereHas('siswa', function ($query) use ($data) {
                            $query->where('id_kelas', $data['kelas']);
                        })
                        ->pluck('id')
                        ->toArray();
                        
                    return redirect()->route('export.presensi.siswa', [
                        'ids' => implode(',', $ids),
                        'type' => $data['type'],
                    ]);
                }),
        ];
    }
}
