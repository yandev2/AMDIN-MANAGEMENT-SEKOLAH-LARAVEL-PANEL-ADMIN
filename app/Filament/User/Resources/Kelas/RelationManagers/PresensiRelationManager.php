<?php

namespace App\Filament\User\Resources\Kelas\RelationManagers;

use App\Models\Mapel;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PresensiRelationManager extends RelationManager
{
    protected static string $relationship = 'presensi';
    protected static ?string $title = 'Presensi';
    protected static string | BackedEnum | null $icon = 'heroicon-o-bookmark-square';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_siswa')
                    ->relationShip('siswa')
                    ->native(false)
                    ->required()
                    ->placeholder('Pilih siswa')
                    ->disabled(fn($record) => $record)
                    ->options(function () {
                        $tanggal = Carbon::today()->toDateString();
                        $kelasId = $this->getOwnerRecord()->id;

                        // Ambil semua siswa di kelas ini
                        $query = Siswa::where('id_kelas', $kelasId);

                        // Ambil ID siswa yang SUDAH absen hari ini
                        $sudahAbsenIds = PresensiSiswa::whereDate('tanggal', $tanggal)
                            ->pluck('id_siswa')
                            ->toArray();

                        // Filter siswa yang BELUM absen
                        $belumAbsen = $query->whereNotIn('id', $sudahAbsenIds)
                            ->pluck('nama_siswa', 'id');

                        return $belumAbsen;
                    }),
                TextInput::make('tahun_ajaran')
                    ->required()
                    ->label('Tahun Ajaran')
                    ->default(fn() => Carbon::now()->format('Y') . '/' . Carbon::now()->addYear()->format('Y'))
                    ->disabled(fn($record) => $record),
                Radio::make('status')
                    ->columns(4)
                    ->columnSpanFull()
                    ->required()
                    ->options([
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alpha',
                    ]),
                Textarea::make('keterangan')
                    ->columnSpanFull()
                    ->label('Keterangan'),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->defaultGroup('tanggal')
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('siswa.nama_siswa')
                    ->searchable(),
                TextColumn::make('status')
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
                TextColumn::make('tahun_ajaran'),
                TextColumn::make('keterangan')
            ])
            ->filters([
                Filter::make('tanggal')
                    ->label('Rentang Tanggal')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date)
                            )
                            ->when(
                                $data['sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['dari'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari'])->translatedFormat('d F Y');
                        }

                        if ($data['sampai'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai'])->translatedFormat('d F Y');
                        }

                        return $indicators;
                    })
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
            ->headerActions([
                CreateAction::make()
                    ->authorize(true)
                    ->button()
                    ->label('Buat Presensi')
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalAlignment(Alignment::Center)
                    ->modalHeading('Absensi')
                    ->modalDescription('absen kehadiran pada siswa ini')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tanggal'] = now()->toDateString();
                        return $data;
                    })
            ])
            ->recordActions([
                EditAction::make()->button()
                    ->authorize(true)
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalAlignment(Alignment::Center)
                    ->modalHeading('Edit Absensi')
                    ->modalDescription('ubah status kehadiran pada siswa ini'),
                DeleteAction::make()->button()
                    ->authorize(true)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(true),
                ]),
            ]);
    }
}
