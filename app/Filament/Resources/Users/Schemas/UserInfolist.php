<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account')
                    ->columnSpanFull()->columns(4)->schema([
                        Grid::make()->schema([
                            TextEntry::make('name')
                                ->label('Nama'),
                            TextEntry::make('sekolah.nama_sekolah')
                                ->label('Sekolah'),
                            TextEntry::make('email')
                                ->label('Email'),
                                 TextEntry::make('created_at')
                                ->label('Joined')
                                ->dateTime('d M Y'),
                        ])->columns(2)->columnSpan(3),
                        ImageEntry::make('sekolah.logo_path')
                            ->hiddenLabel(),
                    ]),
            ]);
    }
}
