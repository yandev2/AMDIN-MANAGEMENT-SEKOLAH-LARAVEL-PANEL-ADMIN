<?php

namespace App\Filament\User\Resources\PresensiGurus\Tables;

use App\Models\Jabatan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PresensiGurusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('tanggal')
            ->groups([
                Group::make('tanggal')
                    ->label('Tanggal')
                    ->titlePrefixedWithLabel(false)
                    ->date()
                    ->collapsible(),
                Group::make('guru.user.name')
                    ->label('Guru')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('absen_masuk')
                    ->label('Kehadiran')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->groupingDirectionSettingHidden()
            ->columns([
                TextColumn::make('guru.user.name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->searchable(),
                TextColumn::make('absen_masuk')
                    ->label('Absen Masuk')
                    ->badge()
                      ->color(fn($state) => match ($state) {
                        'H' => 'success',
                        'I' => 'info',
                        'S' => 'warning',
                        'A' => 'danger'
                    })
                    ->summarize(
                        Summarizer::make()
                            ->label('Rekap Kehadiran')
                            ->using(function ($query) {
                                $counts = $query
                                    ->selectRaw('absen_masuk, COUNT(*) as total')
                                    ->groupBy('absen_masuk')
                                    ->pluck('total', 'absen_masuk');

                                if ($counts->isEmpty()) {
                                    return '-';
                                }
                                $labels = [
                                    'H' => 'Hadir',
                                    'A' => 'Alpa',
                                    'S' => 'Sakit',
                                    'I' => 'Izin',
                                ];
                                return $counts
                                    ->map(function ($total, $key) use ($labels) {
                                        $label = $labels[$key] ?? $key;
                                        return "{$label}: {$total}";
                                    })
                                    ->join(' | ');
                            })

                    ),
                TextColumn::make('lokasi_masuk')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('absen_keluar')
                    ->badge()
                    ->color('success'),
                TextColumn::make('lokasi_keluar')
                    ->badge()
                    ->color('success'),
                TextColumn::make('durasi_kerja')
                    ->time()
                    ->summarize(
                        Summarizer::make()
                            ->label('Rata-rata durasi kerja')
                            ->using(function ($query) {
                                $durations = $query->pluck('durasi_kerja')->filter();

                                if ($durations->isEmpty()) {
                                    return '-';
                                }

                                // Konversi durasi ke detik total
                                $totalSeconds = $durations->sum(function ($time) {
                                    [$h, $m, $s] = array_pad(explode(':', $time), 3, 0);
                                    return ($h * 3600) + ($m * 60) + $s;
                                });

                                $averageSeconds = intval($totalSeconds / $durations->count());

                                // Konversi kembali ke format jam:menit:detik
                                $hours = floor($averageSeconds / 3600);
                                $minutes = floor(($averageSeconds % 3600) / 60);
                                $seconds = $averageSeconds % 60;

                                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                            })
                    ),
                TextColumn::make('status')
                    ->searchable()
                    ->summarize(
                        Summarizer::make()
                            ->label('Rata-rata status kehadiran')
                            ->using(function ($query) {
                                $counts = $query
                                    ->selectRaw('status, COUNT(*) as total')
                                    ->groupBy('status')
                                    ->pluck('total', 'status');

                                if ($counts->isEmpty()) {
                                    return '-';
                                }

                                $labels = [
                                    'H' => 'Hadir',
                                    'T' => 'Tepat Waktu',
                                    'L' => 'Terlambat',
                                    'S' => 'Sakit',
                                    'I' => 'Izin',
                                ];

                                // Cari status terbanyak
                                $max = $counts->max(); // ambil jumlah terbanyak
                                $maxStatus = $counts->filter(fn($total) => $total == $max)->keys()->first();

                                return ($labels[$maxStatus] ?? $maxStatus);
                            })
                    ),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->native(false)
                    ->options([
                        'terlambat' => 'Terlambat',
                        'tepat waktu' => 'Tepat waktu',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit'
                    ]),
                SelectFilter::make('jabatan')
                    ->native(false)
                    ->options(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))
                    ->query(function ($query, $data) {
                        $q = $data['value'];
                        if (blank($q)) return;

                        $query->whereHas('guru.jabatan', function ($guruQuery) use ($q) {
                            $guruQuery->where('nama_jabatan', $q);
                        });
                    }),
                Filter::make('created_at')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('Dari tanggal')->native(false),
                        DatePicker::make('Sampai tanggal')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Dari tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date)
                                    ->when(
                                        $data['Sampai tanggal'],
                                        fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date)
                                    )
                            );
                    }),
            ])
            ->filtersFormWidth('md')
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->badgeColor('danger')
                    ->color('info')
                    ->label('Filter'),
            )
            ->recordActions([
                ViewAction::make()->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
