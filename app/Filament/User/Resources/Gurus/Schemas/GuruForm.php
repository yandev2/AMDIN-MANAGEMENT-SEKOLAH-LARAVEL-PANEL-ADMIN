<?php

namespace App\Filament\User\Resources\Gurus\Schemas;

use App\Models\Jabatan;
use App\Models\JabatanGuru;
use App\Models\Shift;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->columns(1)
                    ->schema([

                        Section::make('Akun')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2
                            ])
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required(),
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // hash kalau diisi
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(string $operation): bool => $operation === 'create'),
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                            ]),

                        Section::make('Data Pribadi')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2
                            ])
                            ->icon('heroicon-o-finger-print')
                            ->relationship('guru')
                            ->schema([
                                Select::make('agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ])
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Agama')
                                    ->required(),
                                Select::make('jk')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Jenis Kelamin')
                                    ->required(),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255),
                                DatePicker::make('tanggal_lahir')
                                    ->native(false)
                                    ->label('Tanggal Lahir'),
                                Textarea::make('alamat')
                                    ->label('Alamat')
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                TextInput::make('pendidikan_terakhir')
                                    ->label('Pendidikan Terakhir')
                                    ->maxLength(255),
                            ])
                    ]),

                Grid::make(1)
                    ->columns(1)
                    ->schema([
                        Section::make('')
                            ->schema([
                                FileUpload::make('foto')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->disk('public')
                                    ->directory('guru')
                                    ->image()
                                    ->panelAspectRatio(2 / 3),
                            ]),

                        Section::make('Data Guru')
                            ->columns([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2
                            ])
                            ->relationship('guru')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->required(),
                                Select::make('status_dinas')
                                    ->options([
                                        'dinas luar' => 'Dinas Luar',
                                        'dinas dalam' => 'Dinas Dalam',
                                    ])
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Status Dinas')
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'active' => 'Aktif',
                                        'inactive' => 'Tidak Aktif',
                                    ])
                                    ->placeholder('')
                                    ->native(false)
                                    ->label('Status Guru')
                                    ->required(),
                                Select::make('id_jabatan')
                                    ->placeholder('')
                                    ->options(Jabatan::pluck('nama_jabatan', 'id'))
                                    ->native(false)
                                    ->label('Jabatan'),
                                Select::make('id_shift')
                                    ->placeholder('')
                                    ->options(Shift::pluck('nama_shift', 'id'))
                                    ->native(false)
                                    ->label('Shift'),
                                TextInput::make('no_hp')
                                    ->label('Nomor HP')
                                    ->tel(),
                            ]),
                    ])
            ]);
    }
}
