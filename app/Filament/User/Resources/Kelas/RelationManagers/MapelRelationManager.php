<?php

namespace App\Filament\User\Resources\Kelas\RelationManagers;

use App\Models\Mapel;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class MapelRelationManager extends RelationManager
{
   protected static string $relationship = 'mapel';
    protected static ?string $title = 'Mapel';
    protected static string | BackedEnum | null $icon = 'heroicon-o-book-open';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hari')
                    ->required()
                    ->reactive()
                    ->native(false)
                    ->options([
                        "senin" => "Senin",
                        "selasa" => "Selasa",
                        "rabu" => "Rabu",
                        "kamis" => "Kamis",
                        "jumat" => "Jumat",
                        "sabtu" => "Sabtu",
                    ]),
                TextInput::make('nama_mapel')
                    ->required(),
                TimePicker::make('jam_masuk')
                    ->required()
                    ->seconds(false),
                TimePicker::make('jam_keluar')
                    ->seconds(false)
                    ->required(),
                Select::make('id_guru')
                    ->native(false)
                    ->label('Guru')
                    ->searchable()
                    ->preload()
                    ->placeholder('')
                    ->columnSpan(2)
                    ->options(function () {
                        return \App\Models\Guru::with('user')
                            ->where('id_sekolah', auth()->user()->id_sekolah)
                            ->whereHas('user.roles', fn($q) => $q->where('name', 'guru'))
                            ->get()
                            ->mapWithKeys(fn($guru) => [
                                $guru->id => $guru->user?->name ?? 'Tanpa Nama'
                            ]);
                    })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->defaultGroup('hari')
            ->columns([
                 TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('nama_mapel')->searchable(),
                TextColumn::make('jam_masuk'),
                TextColumn::make('jam_keluar'),
                TextColumn::make('guru.user.name')->label('Guru')

            ])
            ->recordActions([
                EditAction::make()->button()->color('success') ->authorize(true),
                DeleteAction::make()->button()->color('danger') ->authorize(true)
            ])
            ->headerActions([
                Action::make('import')->label('Import Data Mapel')->button()->color('success'),
                CreateAction::make()
                    ->visible(fn() => true)
                     ->authorize(true)
                    ->label('Tambah Mapel')
                    ->color('primary')
                    ->modalIcon('heroicon-o-book-open')
                    ->modalWidth('lg')
                    ->modalHeading('Tambah Mapel')
                    ->modalDescription('Tambakan Mata Pelajaran Untuk Kelas Ini')
                    ->modalAlignment(Alignment::Center)
                    ->mutateDataUsing(function ($data, $livewire) {
                        $kelas = $livewire->ownerRecord;
                        $isOverlap = Mapel::where('id_kelas', $kelas->id)
                            ->where('hari', $data['hari'])
                            ->where(function ($query) use ($data) {
                                $query->whereBetween('jam_masuk', [$data['jam_masuk'], $data['jam_keluar']])
                                    ->orWhereBetween('jam_keluar', [$data['jam_masuk'], $data['jam_keluar']])
                                    ->orWhere(function ($q) use ($data) {
                                        $q->where('jam_masuk', '<=', $data['jam_masuk'])
                                            ->where('jam_keluar', '>=', $data['jam_keluar']);
                                    });
                            })
                            ->exists();

                        if ($isOverlap) {
                            Notification::make()
                                ->title('Jadwal Bentrok!')
                                ->body('Rentang waktu ini bertabrakan dengan jadwal lain di hari yang sama.')
                                ->danger()
                                ->send();

                            throw ValidationException::withMessages([
                                'jam_masuk' => 'Jadwal ini bertabrakan dengan jadwal lain di hari yang sama.',
                            ]);
                        }

                        $data['id_kelas'] = $kelas->id;
                        $data['id_sekolah'] = $kelas->id_sekolah;

                        return $data;
                    }),
            ])
              ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(true),
                ]),
            ]);
    }
}
