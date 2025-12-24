<?php

namespace App\Filament\User\Resources\Siswas\Pages;

use App\Filament\Imports\SiswaImporter;
use App\Filament\User\Resources\Siswas\SiswaResource;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Alignment;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Siswa')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->modalIcon('heroicon-o-arrow-down-on-square-stack')
                ->modalWidth('md')
                ->color('success')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Export Data Siswa')
                ->modalDescription('export data siswa dalam bentuk pdf atau exel')
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

                            Select::make('kelas')
                                ->label('Kelas')
                                ->native(false)
                                ->required()
                                ->columnSpan(1)
                                ->placeholder('')
                                ->options(options: collect(['all' => 'All'])
                                    ->merge(Kelas::pluck('nama_kelas', 'nama_kelas'))),

                            Select::make('tahun_masuk')
                                ->label('Tahun Masuk')
                                ->columnSpan(2)
                                ->required()
                                ->placeholder('')
                                ->native(false)
                                ->options(function () {
                                    $years = [];
                                    for ($i = date('Y'); $i >= 1970; $i--) {
                                        $years[$i] = $i;
                                    }
                                    return collect(['all' => 'All'])->merge($years);
                                }),
                        ])
                ])
                ->action(function ($data) {
                    $tahun_masuk = $data['tahun_masuk'];
                    $kelas = $data['kelas'];
                    $type = $data['type'];

                    $query = Siswa::query();

                    // Filter kelas
                    if (!empty($kelas) && $kelas !== 'all') {
                        $query->whereHas('kelas', function ($q) use ($kelas) {
                            $q->where('nama_kelas', $kelas);
                        });
                    }

                    // Filter kelas
                    if (!empty($tahun_masuk) && $tahun_masuk !== 'all') {
                        $query->where('tahun_masuk', $tahun_masuk);
                    }

                    $userIds = $query->pluck('id')->toArray();

                    return redirect()->route('export.siswa', [
                        'ids' => implode(',', $userIds),
                        'type' => $type,
                        'kelas'=> $kelas
                    ]);
                }),

                 ImportAction::make()
                ->options([
                    'id_sekolah' => auth()->user()->id_sekolah
                ])
                ->icon('heroicon-o-arrow-up-on-square-stack')
                ->label('Import Siswa')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Import Data Siswa')
                ->modalDescription('pastikan file csv anda valid dengan data example csv, perhatikan kelas(nama kelas sudah ada di master kelas kosongkan jika tidak ada kelas)')
                ->modalWidth('md')
                ->color('warning')
                ->modalIcon('heroicon-o-arrow-up-on-square-stack')
                ->importer(SiswaImporter::class)
                ->extraModalFooterActions([
                    Action::make('download-example-csv')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(
                            fn() =>
                            asset('storage/template/template_import_siswa.csv'),
                            shouldOpenInNewTab: true
                        ),
                ]),

            CreateAction::make()
                ->label('Tambah Siswa')
                ->icon('heroicon-o-user-plus'),
        ];
    }
}
