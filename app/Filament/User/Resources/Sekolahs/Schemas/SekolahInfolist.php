<?php

namespace App\Filament\User\Resources\Sekolahs\Schemas;

use App\Filament\User\Resources\Gurus\GuruResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SekolahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->columnSpan(1)->columns(1)->schema([
                    Section::make('')->columns(2)->schema([
                        TextEntry::make('nama_sekolah'),
                        TextEntry::make('npsn'),
                        TextEntry::make('level'),
                        TextEntry::make('status')->color('success'),
                        TextEntry::make('no_tlp'),
                        TextEntry::make('email')
                            ->label('Email address'),
                    ]),
                    Section::make('')->columns(2)->schema([
                        TextEntry::make('kota'),
                        TextEntry::make('provinsi'),
                        TextEntry::make('alamat')->columnSpan(2),
                        TextEntry::make('location'),
                        TextEntry::make('website'),
                    ]),
                ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Kepala Sekolah')
                            ->columns(2)
                            ->headerActions([
                                Action::make('Lihat')
                                    ->button()
                                    ->color('info')
                                    ->icon('heroicon-o-eye')
                                    ->visible(fn($record) => $record->kepalaSekolah->first() !== null)
                                    ->url(fn($record) => GuruResource::getUrl('view', ['record' => $record->kepalaSekolah()->first()->user->id]))
                            ])
                            ->schema([
                                ImageEntry::make('foto')
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->getStateUsing(function ($record) {
                                        $kepala = $record->kepalaSekolah->first();
                                        return $kepala ? $kepala->user->foto : '-';
                                    }),
                                TextEntry::make('kepalaSekolah')
                                    ->label('Nama')
                                    ->getStateUsing(function ($record) {
                                        $kepala = $record->kepalaSekolah->first();
                                        return $kepala ? $kepala->user->name : '-';
                                    }),
                                TextEntry::make('kepalaSekolah')
                                    ->label('Nip')
                                    ->getStateUsing(function ($record) {
                                        $kepala = $record->kepalaSekolah->first();
                                        return $kepala ? $kepala->nip : '-';
                                    })
                            ]),
                        Section::make('')->columns(2)->schema([
                            ImageEntry::make('logo_path')
                                ->defaultImageUrl(url('storage/sekolah/logo/logo_default.png'))
                                ->disk('public')
                                ->alignCenter()
                                ->hiddenLabel()
                                ->imageSize(250)
                                ->columnSpanFull(),

                        ]),
                    ])
            ]);
    }
}
