<?php

namespace App\Filament\User\Resources\PresensiGurus\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PresensiGuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_sekolah')
                    ->required()
                    ->numeric(),
                TextInput::make('id_guru')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('jam_masuk')
                    ->required(),
                TimePicker::make('jam_keluar')
                    ->required(),
                TextInput::make('absen_masuk')
                    ->required(),
                TextInput::make('lokasi_masuk')
                    ->required(),
                TextInput::make('absen_keluar'),
                TextInput::make('lokasi_keluar'),
                TimePicker::make('durasi_kerja'),
                TextInput::make('dokumen'),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                TextInput::make('face'),
                TextInput::make('status'),
            ]);
    }
}
