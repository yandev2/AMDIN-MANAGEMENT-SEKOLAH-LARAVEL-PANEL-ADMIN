<?php

namespace App\Filament\User\Resources\Gurus\Pages;

use App\Filament\Imports\UserImporter;
use App\Filament\User\Resources\Gurus\GuruResource;
use App\Models\Jabatan;
use App\Models\User;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Schemas\Components\Grid;

class ListGurus extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Guru')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->modalIcon('heroicon-o-arrow-down-on-square-stack')
                ->modalWidth('md')
                ->color('success')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Export Data Guru')
                ->modalDescription('export data guru dalam bentuk pdf atau exel')
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
                            Select::make('status_dinas')
                                ->label('Status Dinas')
                                ->native(false)
                                ->required()
                                ->placeholder('')
                                ->options([
                                    'all' => 'All',
                                    'dinas luar' => 'Dinas Luar',
                                    'dinas dalam' => 'Dinas Dalam'
                                ]),
                            Select::make('jabatan')
                                ->label('Jabatan')
                                ->native(false)
                                ->required()
                                ->columnSpan(2)
                                ->placeholder('')
                                ->options(options: collect(['all' => 'All'])
                                    ->merge(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))),
                        ]),

                    Select::make('guru')
                        ->multiple()
                        ->required()
                        ->native(false)
                        ->preload()
                        ->placeholder('')
                        ->options(fn() =>   User::role(['guru', 'kepala_sekolah',])->pluck('name', 'id'))
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
                ->action(function ($data) {
                    $jabatan = $data['jabatan'];
                    $status_dinas = $data['status_dinas'];
                    $guruIds = $data['guru'] ?? [];

                    $query = User::query();

                    // Filter jabatan
                    if (!empty($jabatan) && $jabatan !== 'all') {
                        $query->whereHas('guru.jabatan', function ($q) use ($jabatan) {
                            $q->where('nama_jabatan', $jabatan);
                        });
                    }

                    // Filter status dinas
                    if (!empty($status_dinas) && $status_dinas !== 'all') {
                        $query->whereHas('guru', function ($q) use ($status_dinas) {
                            $q->where('status_dinas', $status_dinas);
                        });
                    }

                    // Filter berdasarkan guru yang dipilih
                    if (!empty($guruIds)) {
                        $query->whereIn('id', $guruIds);
                    }

                    // Ambil id user yang cocok
                    $userIds = $query->pluck('id')->toArray();

                    // Redirect dengan parameter
                    return redirect()->route('export.guru', [
                        'ids' => implode(',', $userIds),
                        'type' => $data['type'],
                        'status_dinas' => $data['status_dinas']
                    ]);
                }),

            ImportAction::make()
                ->options([
                    'id_sekolah' => auth()->user()->id_sekolah
                ])
                ->icon('heroicon-o-arrow-up-on-square-stack')
                ->label('Import Guru')
                ->modalAlignment(Alignment::Center)
                ->modalHeading('Import Data Guru')
                ->modalDescription('pastikan file csv anda valid dengan data example csv, perhatikan jabatan(nama jabatan sudah ada di master jabatan), shift(nama shift sudah ada di master shift), status(active / inactive) dan status_dinas(dinas luar / dinas dalam)')
                ->modalWidth('md')
                ->color('warning')
                ->modalIcon('heroicon-o-arrow-up-on-square-stack')
                ->importer(UserImporter::class)
                ->extraModalFooterActions([
                    Action::make('download-example-csv')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(
                            fn() =>
                            asset('storage/template/template_import_guru.csv'),
                            shouldOpenInNewTab: true
                        ),
                ]),

            CreateAction::make()
                ->label('Tambah Guru')
                ->icon('heroicon-o-user-plus'),

        ];
    }
}
