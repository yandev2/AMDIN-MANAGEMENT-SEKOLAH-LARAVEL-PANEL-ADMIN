<?php

namespace App\Filament\User\Resources\PresensiGurus\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

class PresensiGuruInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                Section::make()->columnSpan(1)->columns(4)->schema([
                    TextEntry::make('guru.user.name'),
                    TextEntry::make('tanggal')
                        ->date('d M Y'),
                    TextEntry::make('durasi_kerja')
                        ->time(),
                    TextEntry::make('status'),
                    ImageEntry::make('face')
                        ->getStateUsing(fn() => 'guru/01K80FEW39ZKQ6BR4M7GRSSDX8.jpeg')
                        ->imageSize('100px')
                        ->disk('public'),
                    TextEntry::make('download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn($record) => asset('storage/' . $record->dokumen), shouldOpenInNewTab: true)
                        ->visible(function ($record) {
                            $path = $record->dokumen ?? null;
                            if (! $path) return false;
                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            return ! in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                        }),
                    MediaAction::make('priview')
                        ->button()
                        ->icon('heroicon-o-eye')
                        ->modalHeading(fn($record) => $record->guru->user->name)
                        ->media(fn($record) => str_replace(' ', '%20', Storage::url($record->dokumen)))
                        ->visible(function ($record) {
                            $path = $record->dokumen ?? null;
                            if (! $path) return false;
                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            return in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                        }),

                ]),

                Section::make()->columnSpan(1)->columns(3)->schema([
                    TextEntry::make('absen_masuk'),
                    TextEntry::make('lokasi_masuk'),
                    TextEntry::make('absen_keluar'),
                    TextEntry::make('lokasi_keluar'),
                    TextEntry::make('jam_masuk')
                        ->label('Jam Masuk')
                        ->time(),
                    TextEntry::make('jam_keluar')
                        ->label('Jam Keluar')
                        ->time(),
                    TextEntry::make('keterangan')
                        ->columnSpan(2),
                ]),
            ]);
    }
}
