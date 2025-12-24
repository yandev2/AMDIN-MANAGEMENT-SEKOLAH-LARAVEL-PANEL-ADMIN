<?php

namespace App\Filament\User\Resources\Siswas\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('')
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make('Data pribadi siswa')
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 3,
                                'xl' => 4
                            ])
                            ->schema([
                                TextEntry::make('nama_siswa')->label('Nama Lengkap'),
                                TextEntry::make('jk')->label('Jenis Kelamin'),
                                TextEntry::make('tempat_lahir')->label('Tempat Lahir'),
                                TextEntry::make('tanggal_lahir')->label('Tanggal Lahir')->date(),
                                TextEntry::make('agama')->label('Agama'),
                                TextEntry::make('alamat')->label('Alamat'),
                                TextEntry::make('nik')->label('NIK'),
                                TextEntry::make('no_kk')->label('No. KK'),
                            ]),
                        Tab::make('Informasi Pendidikan')
                            ->columns(['lg' => 4, 'md' => 2])
                            ->schema([
                                TextEntry::make('nis')->label('NIS'),
                                TextEntry::make('nisn')->label('NISN'),
                                TextEntry::make('tahun_masuk')->label('Tahun Masuk'),
                                TextEntry::make('kelas')->label('Kelas')
                                    ->getStateUsing(function ($record) {
                                        if ($record->kelas) {
                                            return 'Kelas ' . $record->kelas->nama_kelas;
                                        }
                                        return 'Belum Ada Kelas';
                                    }),

                            ]),
                        Tab::make('Keluarga Siswa')
                            ->columns(['lg' => 4, 'md' => 2])
                            ->schema([
                                RepeatableEntry::make('nokSiswa')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('nama'),
                                        TextEntry::make('no_tlp'),
                                        TextEntry::make('hubungan'),
                                    ])
                                    ->columns([
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 2,
                                        'xl' => 3
                                    ])
                                    ->columnSpanFull()
                                    ->grid([
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 2,
                                        'xl' => 2
                                    ])


                            ]),
                    ]),

                Section::make('')->schema([
                    ImageEntry::make('foto')
                        ->hiddenLabel()
                        ->alignCenter()
                        ->imageSize('100%') // fleksibel penuh
                        ->disk('public'),
                ]),

                Grid::make(1)->schema([
                    Section::make(fn($record) => 'Kehadiran Siswa Tahun Ajaran: ' . ($record->tahunAjaranTerbaru() ?? '-'))
                        ->columns([
                               'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                                'xl' => 4
                        ])
                        ->schema([
                            TextEntry::make('jumlah_presensi')->label('Jumlah Presensi')
                                ->columnSpanFull()
                                ->formatStateUsing(fn($record) => $record->presensiSiswa ? $record->presensiSiswa->count() . ' Presensi' : '0 Presensi'),
                            TextEntry::make('h')->label('Hadir')
                                ->badge()
                                ->color('success')
                                ->getStateUsing(function ($record) {
                                    $data = $record->presensiSiswa()->where('status', 'H')->count();
                                    return $data . ' Hari';
                                }),
                            TextEntry::make('i')->label('Izin')
                                ->badge()
                                ->color('warning')
                                ->getStateUsing(function ($record) {
                                    $data = $record->presensiSiswa()->where('status', 'I')->count();
                                    return $data . ' Hari';
                                }),
                            TextEntry::make('s')->label('Sakit')
                                ->badge()
                                ->color('info')
                                ->getStateUsing(function ($record) {
                                    $data = $record->presensiSiswa()->where('status', 'S')->count();
                                    return $data . ' Hari';
                                }),
                            TextEntry::make('a')->label('Alpa')
                                ->badge()
                                ->color('danger')
                                ->getStateUsing(function ($record) {
                                    $data = $record->presensiSiswa()->where('status', 'A')->count();
                                    return $data . ' Hari';
                                }),
                        ])
                ])

            ]);
    }
}
