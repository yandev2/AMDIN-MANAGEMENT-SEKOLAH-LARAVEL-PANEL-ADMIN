<?php

namespace App\Filament\User\Resources\Sekolahs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        Section::make('Informasi Sekolah')
                            ->columns(2)
                            ->schema([
                                TextInput::make('nama_sekolah')
                                    ->required(),
                                TextInput::make('npsn'),
                                Select::make('level')
                                    ->native(false)
                                    ->disabled()
                                    ->options([
                                        'SD' => 'SD',
                                        'SMP' => 'SMP',
                                        'SMA' => 'SMA',
                                        'SMK' => 'SMK',
                                    ])->required(),
                                Select::make('status')
                                    ->required()
                                    ->disabled()
                                    ->native(false)
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                            ]),

                        Section::make('Address Sekolah')
                            ->columns(2)
                            ->schema([
                                Textarea::make('alamat')
                                    ->columnSpanFull(),
                                TextInput::make('kota'),
                                TextInput::make('provinsi'),
                                TextInput::make('no_tlp'),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email(),
                                TextInput::make('website'),
                                TextInput::make('location'),
                            ]),
                    ]),

                Grid::make()
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        FileUpload::make('logo_path')
                            ->hiddenLabel()
                            ->imageEditor()
                            ->alignment('center')
                            ->alignCenter()
                            ->panelAspectRatio('1:1')
                            ->directory('sekolah/logo')
                            ->disk('public'),

                    ])
            ]);
    }
}

