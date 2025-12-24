<?php

namespace App\Filament\User\Resources\Gurus\Schemas;

use App\Filament\User\Resources\Kelas\KelasResource;
use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class GuruInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Informasi Dasar')
                            ->description('Data pribadi guru.')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2
                            ])
                            ->schema([
                                TextEntry::make('name')->label('Nama Lengkap'),
                                TextEntry::make('guru.nip')->label('NIP'),
                                TextEntry::make('guru.jk')->label('Jenis Kelamin'),
                                TextEntry::make('guru.agama')->label('Agama'),
                                TextEntry::make('guru.tempat_lahir')->label('Tempat Lahir'),
                                TextEntry::make('guru.tanggal_lahir')->label('Tanggal Lahir')->date(),
                                TextEntry::make('guru.pendidikan_terakhir')->label('Pendidikan Terakhir'),
                                TextEntry::make('guru.alamat')->label('Alamat'),
                            ]),
                        Section::make('Informasi Dinas')
                            ->description('Status kepegawaian dan kontak.')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2
                            ])
                            ->schema([
                                TextEntry::make('guru.status_dinas')->label('Status Dinas'),
                                TextEntry::make('guru.status')->label('Status Pegawai'),
                                TextEntry::make('email')->label('Email'),
                                TextEntry::make('guru.no_hp')->label('Nomor HP'),
                                TextEntry::make('guru.shift.nama_shift')->label('Shift Kerja'),
                                TextEntry::make('guru.jabatan.nama_jabatan')->label('Jabatan'),
                            ]),
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('')->schema([
                            ImageEntry::make('foto')
                                ->hiddenLabel()
                                ->alignCenter()
                                ->imageSize('100%') // fleksibel penuh
                                ->disk('public'),
                        ]),

                        Section::make('')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 3
                            ])
                            ->schema([
                                TextEntry::make('guru.kelas.nama_kelas')->label('Kelas')
                                    ->columnSpan(2)
                                    ->getStateUsing(function ($record) {
                                        if ($record->guru->kelas) {
                                            return 'Kelas ' . $record->guru->kelas->nama_kelas . ' Jumlah Siswa ' . $record->guru->kelas->siswa->count();
                                        }
                                        return 'Belum Ada Kelas';
                                    }),

                                Actions::make([
                                    Action::make('Lihat Kelas')
                                        ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->button()
                                        ->visible(fn($record) => $record->guru && $record->guru->kelas)
                                        ->url(function ($record) {
                                            $kelas = $record->guru->kelas ?? null;
                                            return $kelas
                                                ? KelasResource::getUrl('view', ['record' => $kelas['id']])
                                                : null;
                                        }),
                                ])->columnSpan(1)->columns(1)
                            ]),

                        Section::make('Presensi Bulan Ini')
                            ->columns([
                                   'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 3
                            ])
                            ->schema([
                                TextEntry::make('h')->label('Hadir')
                                    ->badge()
                                    ->color('success')
                                    ->getStateUsing(function ($record) {
                                        $data = $record->guru->presensiGuru()
                                            ->whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->where('absen_masuk', 'H')->count();
                                        return $data . ' Hari';
                                    }),

                                TextEntry::make('i')->label('Izin')
                                    ->badge()
                                    ->color('warning')
                                    ->getStateUsing(function ($record) {
                                        $data = $record->guru->presensiGuru()
                                            ->whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->where('absen_masuk', 'I')->count();
                                        return $data . ' Hari';
                                    }),

                                TextEntry::make('s')->label('Sakit')
                                    ->badge()
                                    ->color('danger')
                                    ->getStateUsing(function ($record) {
                                        $data = $record->guru->presensiGuru()
                                            ->whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->where('absen_masuk', 'S')->count();
                                        return $data . ' Hari';
                                    }),

                                TextEntry::make('rata_rata')->label('Rata rata durasi kerja')
                                    ->badge()
                                    ->color('info')
                                    ->getStateUsing(function ($record) {
                                        $durasiList = $record->guru->presensiGuru()
                                            ->whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->pluck('durasi_kerja'); // hasilnya collection berisi string "HH:MM:SS"


                                        $rataRataJam = $durasiList->map(function ($durasi) {
                                            // pecah waktu jadi jam, menit, detik
                                            if ($durasi) {
                                                [$jam, $menit, $detik] = explode(':', $durasi);

                                                // ubah ke format jam desimal
                                                return $jam + ($menit / 60) + ($detik / 3600);
                                            }
                                        })->avg(); // hitung rata-rata

                                        return round($rataRataJam, 2) . ' Jam';
                                    }),

                                Actions::make([
                                    Action::make('detail')
                                        ->label('Lihat Daftar Presensi')
                                        ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->size('md')
                                        ->link()
                                        ->url(fn($record) => PresensiGuruResource::getUrl() . '?' .  http_build_query([
                                            'search' => $record->name
                                        ])),
                                ])->columnSpan(2)
                            ])
                    ]),
            ]);
    }
}
