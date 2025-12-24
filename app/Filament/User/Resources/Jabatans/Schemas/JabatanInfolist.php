<?php

namespace App\Filament\User\Resources\Jabatans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JabatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')->columnSpanFull()->columns(3)->schema([
                    TextEntry::make('nama_jabatan'),
                    TextEntry::make('gaji')
                        ->numeric(),
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime(),
                    TextEntry::make('jumlah_guru')
                        ->getStateUsing(fn($record) => $record->guru->count() . ' Guru'),
                ]),
            ]);
    }
}
