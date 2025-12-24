<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account')
                    ->columnSpanFull()->columns(3)->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(fn($record) => !$record),
                    ]),

                Section::make('Sekolah')
                    ->columnSpanFull()->columns(3)->schema([
                        TextInput::make('nama_sekolah')
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record && $record->sekolah) {
                                    $set('nama_sekolah', $record->sekolah->nama_sekolah);
                                }
                            })
                            ->required(),
                        Select::make('level')
                            ->native(false)
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                                'SMK' => 'SMK',
                            ])
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record && $record->sekolah) {
                                    $set('level', $record->sekolah->level);
                                }
                            })
                            ->required(),
                        Select::make('status')
                            ->native(false)
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ])
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record && $record->sekolah) {
                                    $set('status', $record->sekolah->status);
                                }
                            })
                            ->required(),
                    ])
            ]);
    }
}