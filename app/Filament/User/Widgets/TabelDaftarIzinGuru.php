<?php

namespace App\Filament\User\Widgets;

use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use App\Models\Jabatan;
use App\Models\PresensiGuru;
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

class TabelDaftarIzinGuru extends TableWidget
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
                'title' => 'Guru Tidak Hadir Hari Ini'
            ])->render()

        );
    }



    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => PresensiGuru::query()->where('tanggal', Carbon::now())->whereIn('absen_masuk', ['I', 'S']))
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('guru.user.name')
                    ->label('Nama')
                    ->width(300),
                TextColumn::make('absen_masuk')
                    ->label('Absen')
                    ->badge()
                    ->color(fn($record) => match ($record->absen_masuk) {
                        'H' => 'success',
                        'I' => 'warning',
                        'S' => 'danger'
                    })
                    ->formatStateUsing(fn($state) => $state == 'I' ? 'Izin' : 'Sakit'),

                TextColumn::make('keterangan'),

            ])
            ->filters([
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
                MediaAction::make('priview_dokumen')
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

                Action::make('priview_dokumen')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => $record->guru->user->name)
                    ->url(fn($record) => asset('storage/' . $record->dokumen), shouldOpenInNewTab: true)
                    ->visible(function ($record) {
                        $path = $record->dokumen ?? null;
                        if (! $path) return false;
                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        return ! in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                    }),

                Action::make('view_detail')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => PresensiGuruResource::getUrl('view', ['record' => $record->id]))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
