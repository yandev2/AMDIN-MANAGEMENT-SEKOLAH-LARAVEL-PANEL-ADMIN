<?php

namespace App\Filament\User\Resources\PresensiGurus\Pages;

use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use App\Models\PresensiGuru;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Schemas\Components\Grid;

class ListPresensiGurus extends ListRecords
{
    protected static string $resource = PresensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Presensi')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->modalIcon('heroicon-o-arrow-down-on-square-stack')
                ->modalWidth('md')
                ->color('success')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Export Presensi Guru')
                ->modalDescription('export data presensi guru dalam bentuk pdf atau exel')
                ->modalWidth('md')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('type')
                                ->label('Tipe File')
                                ->native(false)
                                ->required()
                                ->columnSpan(1)
                                ->placeholder('')
                                ->options([
                                    'exel' => 'Exel',
                                    'pdf' => 'Pdf'
                                ]),
                            DatePicker::make('bulan')
                                ->required()
                                ->native(false)
                                ->displayFormat('F Y')
                                ->format('Y-m')
                                ->closeOnDateSelection()
                                ->label('Pilih Bulan dan Tahun'),
                            Select::make('guru')
                                ->multiple()
                                ->required()
                                ->native(false)
                                ->preload()
                                ->columnSpanFull()
                                ->placeholder('')
                                ->options(fn() => User::role('guru')->pluck('name', 'id'))
                                ->extraAttributes(['class' => 'select-guru'])
                                ->afterStateHydrated(function ($component, $state) {
                                    if (is_null($state)) {
                                        $component->state([]);
                                    }
                                })
                                ->extraAttributes([
                                    'style' => 'max-height: 200px; overflow-y: auto;'
                                ])
                                ->suffixAction(
                                    Action::make('selectAll')
                                        ->label('Pilih Semua')
                                        ->color('success')
                                        ->icon('heroicon-m-check-circle')
                                        ->action(function ($set) {
                                            $set('guru', User::role('guru')->pluck('id')->toArray());
                                        })
                                ),
                        ])
                ])
                ->action(function ($data) {
                    $bulanTahun = $data['bulan']; // "2025-10"
                    [$tahun, $bulan] = explode('-', $bulanTahun);
                    $ids = PresensiGuru::query()
                        ->whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan)
                        ->whereIn('id_guru', $data['guru'])
                        ->pluck('id')
                        ->toArray();
                    return redirect()->route('export.presensi.guru', [
                        'ids' => implode(',', $ids),
                        'type' => $data['type'],
                    ]);
                }),

        ];
    }
}
