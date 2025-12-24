<?php

namespace App\Filament\User\Resources\Kelas\Schemas;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns([
                        'sm'=>1,
                        'md'=> 1,
                        'lg'=>1,
                        'xl'=>2
                    ])
                    ->schema([
                        ImageEntry::make('guru.user.foto')
                            ->disk('public')
                            ->hiddenLabel()
                            ->alignCenter()
                            ->columnSpanFull()
                            ->imageSize('100%'),

                        TextEntry::make('guru.user.name')->label('Wali Kelas'),
                        TextEntry::make('guru.nip')->label('Nip'),
                        Actions::make([
                            Action::make('View Detail')
                        ]),
                    ]),
                Section::make('')
                    
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ])
                    ->schema([
                        TextEntry::make('nama_kelas'),
                        TextEntry::make('tingkat'),
                        TextEntry::make('deskripsi')->columnSpan(1),
                        TextEntry::make('siswa')->label('Jumlah Siswa')
                            ->getStateUsing(function ($record) {
                                if ($record->siswa) {
                                    return  $record->siswa->count() . ' Siswa';
                                }
                                return 'Belum Ada Siswa';
                            }),
                        TextEntry::make('Presensi siswa')
                            ->getStateUsing(function ($record) {
                                $jmlhSiwsa = 0;
                                if ($record->siswa) {
                                    $jmlhSiwsa =  $record->siswa->count();
                                }

                                $jmlhPresensi = $record->presensi->where('tanggal', Carbon::today()->toDateString())->count();
                                return ($jmlhSiwsa - $jmlhPresensi) == 0 ? 'semua siswa sudah di absen pada hari ini' : ($jmlhSiwsa - $jmlhPresensi) . ' siswa belum dilakukan absen hari ini';
                            })
                    ]),

            ]);
    }
}
