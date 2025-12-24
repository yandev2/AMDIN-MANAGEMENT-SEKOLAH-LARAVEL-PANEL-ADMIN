<?php

namespace App\Filament\User\Resources\Shifts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShiftInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 3
                    ])->schema([
                        TextEntry::make('nama_shift'),
                        TextEntry::make('jam_masuk')
                            ->time(),
                        TextEntry::make('jam_keluar')
                            ->time(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),


            ]);
    }
}
