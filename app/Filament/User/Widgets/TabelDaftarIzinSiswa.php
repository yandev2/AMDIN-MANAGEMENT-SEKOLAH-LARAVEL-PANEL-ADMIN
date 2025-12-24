<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use App\Filament\User\Resources\PresensiSiswas\PresensiSiswaResource;
use App\Models\Jabatan;
use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Filament\Widgets\TableWidget;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class TabelDaftarIzinSiswa extends TableWidget
{

    // protected string $view = 'filament.widgets.tabel-daftar-izin-guru';

    protected static ?string $heading = '';

    protected int | string | array $columnSpan = 2;

    public function mount(): void
    {
        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_START,
            fn() =>
            view('component.heading.tabel-heading', [
                'title' => 'Siswa Tidak Hadir Hari Ini'
            ])->render()

        );
    }



    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => PresensiSiswa::query()->where('tanggal', Carbon::now())->whereIn('status', ['A', 'I', 'S']))
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('siswa.nama_siswa')
                    ->label('Nama')
                    ->width(300),
                TextColumn::make('status')
                    ->label('Absen')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'H' => 'success',
                        'I' => 'info',
                        'S' => 'warning',
                        'A' => 'danger'
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alpa'
                    }),

                TextColumn::make('keterangan'),

            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->native(false)
                    ->options(Kelas::pluck('nama_kelas', 'id'))
                    ->query(function ($query, $data) {
                        $q = $data['value'];
                        if (blank($q)) return;

                        $query->whereHas('siswa', function ($guruQuery) use ($q) {
                            $guruQuery->where('id_kelas', $q);
                        });
                    }),
            ])
            ->filtersFormWidth('sm')
            ->filtersFormColumns(1)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->badgeColor('danger')
                    ->color('info')
                    ->label('Filter'),
            )
            ->headerActions([
                //
            ])
            ->recordActions([
             

                Action::make('view_detail')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => PresensiSiswaResource::getUrl('view', ['record' => $record->id]))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
