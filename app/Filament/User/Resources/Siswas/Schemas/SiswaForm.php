<?php

namespace App\Filament\User\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([

                Section::make('')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ])
                    ->schema([
                        TextInput::make('nis')
                            ->required(),
                        TextInput::make('nisn')
                            ->required(),
                        TextInput::make('nama_siswa')
                            ->required(),
                        Select::make('jk')
                            ->label('Jenis Kelamin')
                            ->required()
                            ->placeholder('')
                            ->native(false)
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),
                        Textarea::make('tempat_lahir')
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('alamat')
                            ->required()
                            ->columnSpan(2),

                        Select::make('id_kelas')
                            ->native(false)
                            ->placeholder('')
                            ->relationship('kelas', 'nama_kelas'),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->placeholder('')
                            ->native(false)
                            ->default('aktif')
                            ->options([
                                'aktif' => 'aktif',
                                'lulus' => 'lulus',
                            ]),
                        Select::make('agama')
                            ->label('Agama')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('')
                            ->native(false)
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu',
                            ]),
                    ]),


                Section::make('')
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 2
                    ])
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('foto')
                            ->hiddenLabel()
                            ->columnSpan(2)
                            ->disk('public')
                            ->directory('siswa')
                            ->image()
                            ->imageEditor()
                            ->panelAspectRatio(1 / 1.75),

                        Select::make('tahun_masuk')
                            ->label('Tahun Masuk')
                            ->required()
                            ->placeholder('')
                            ->native(false)
                            ->options(function () {
                                $years = [];
                                for ($i = date('Y'); $i >= 1970; $i--) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            }),
                        TextInput::make('nik')->numeric()->nullable(),
                        TextInput::make('no_kk')->numeric()->nullable(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false),
                    ]),

                Repeater::make('qualifications')
                    ->relationship('nokSiswa')
                    ->columns(2)
                    ->columnSpan(2)
                    ->grid(2)
                    ->addActionAlignment(Alignment::Start)
                    ->addActionLabel('Add Hubungan Keluarga')
                    ->schema([
                        TextInput::make('nama')->label('Nama')->required(),
                        TextInput::make('no_tlp')->label('Nomor Telepon')->required(),
                        Select::make('hubungan')
                            ->label('Hubungan')
                            ->required()
                            ->native(false)
                            ->columnSpan(2)
                            ->placeholder('')
                            ->options([
                                'Ayah' => 'Ayah',
                                'Ibu' => 'Ibu',
                                'Wali' => 'Wali',
                                'Saudara' => 'Saudara',
                            ]),
                    ])

            ]);
    }
}
