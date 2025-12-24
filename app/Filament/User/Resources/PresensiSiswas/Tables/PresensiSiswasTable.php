<?php

namespace App\Filament\User\Resources\PresensiSiswas\Tables;

use App\Models\Kelas;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
use Illuminate\Database\Eloquent\Builder;

class PresensiSiswasTable
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
                Group::make('siswa.nama_siswa')
                    ->label('Siswa')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('status')
                    ->label('Kehadiran')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
                Group::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->groupingDirectionSettingHidden()
            ->columns([
                TextColumn::make('siswa.nama_siswa')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->searchable(),
                TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
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
                                    ->selectRaw('status, COUNT(*) as total')
                                    ->groupBy('status')
                                    ->pluck('total', 'status');

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
                TextColumn::make('keterangan')
                    ->label('Keterangan')
            ])
            ->filters([
                SelectFilter::make('status')
                    ->native(false)
                    ->multiple()
                    ->options([
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alpha'
                    ]),

                SelectFilter::make('kelas')
                    ->native(false)
                    ->multiple()
                    ->options(Kelas::pluck('nama_kelas', 'id'))
                    ->query(function (Builder $query, $data) {
                        if (blank($data)) {
                            return;
                        }
                        $ids = collect($data)
                            ->flatten() // ratakan nested array
                            ->filter()  // hapus null / kosong
                            ->toArray();

                        if (empty($ids)) {
                            return;
                        }
                        $query->whereHas('siswa.kelas', function ($kelasQuery) use ($ids) {
                            $kelasQuery->whereIn('id', $ids);
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

                EditAction::make()->button(),
                DeleteAction::make()->button()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
