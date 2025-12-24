<?php

namespace App\Filament\User\Resources\Jabatans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jabatan Guru')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_jabatan')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('gaji')
                            ->prefix('Rp ')
                            ->required()
                            ->numeric(),
                        Textarea::make('deskripsi')
                            ->columnSpanFull(),

                    ]),
            ]);
    }
}
