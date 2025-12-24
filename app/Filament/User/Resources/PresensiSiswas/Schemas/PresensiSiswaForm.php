<?php

namespace App\Filament\User\Resources\PresensiSiswas\Schemas;

use App\Models\PresensiSiswa;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PresensiSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make()
               ->schema([
                        Select::make('id_siswa')
                    ->relationShip('siswa')
                    ->native(false)
                    ->required()
                    ->placeholder('Pilih siswa')
                    ->disabled(fn($record) => $record)
                    ->options(Siswa::pluck('nama_siswa', 'id')),
                TextInput::make('tahun_ajaran')
                    ->required()
                    ->label('Tahun Ajaran')
                    ->default(fn() => Carbon::now()->format('Y') . '/' . Carbon::now()->addYear()->format('Y'))
                    ->disabled(fn($record) => $record),
                Radio::make('status')
                    ->columns(4)
                    ->columnSpanFull()
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alpha',
                    ]),
                Textarea::make('keterangan')
                    ->columnSpanFull()
                    ->label('Keterangan'),
               ])
            ]);
    }
}
