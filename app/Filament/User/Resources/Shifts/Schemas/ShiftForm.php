<?php

namespace App\Filament\User\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns([
                           'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 4
                    ])
                    ->schema([
                        TextInput::make('nama_shift')
                            ->required()
                            ->columnSpan(2),
                        TimePicker::make('jam_masuk')
                            ->required()
                            ->columnSpan(1),
                        TimePicker::make('jam_keluar')
                            ->required()
                            ->columnSpan(1),
                        Textarea::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}