<?php

namespace App\Filament\User\Resources\Kelas\Schemas;

use App\Models\Guru;
use App\Models\Sekolah;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 3
                    ])
                    ->schema([
                        Select::make('tingkat')
                            ->required()
                            ->native(false)
                            ->preload()
                            ->options(function () {
                                $tingkatSekolah = Sekolah::find(auth()->user()->id_sekolah)->level;
                                return match ($tingkatSekolah) {
                                    'SD' => [
                                        1 => '1 (Satu)',
                                        2 => '2 (Dua)',
                                        3 => '3 (Tiga)',
                                        4 => '4 (Empat)',
                                        5 => '5 (Lima)',
                                        6 => '6 (Enam)',
                                    ],
                                    'SMP' => [
                                        7 => '7 (Tujuh)',
                                        8 => '8 (Delapan)',
                                        9 => '9 (Sembilan)',
                                    ],
                                    'SMA' => [
                                        10 => '10 (Sepuluh)',
                                        11 => '11 (Sebelas)',
                                        12 => '12 (Dua Belas)',
                                    ]
                                };
                            }),

                        TextInput::make('nama_kelas')
                            ->required(),

                        Select::make('id_guru')
                            ->native(false)
                            ->preload()
                            ->placeholder('')
                            ->searchable()
                            ->options(Guru::with('user')
                                ->get()
                                ->pluck('user.name', 'id')),
                        Textarea::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
