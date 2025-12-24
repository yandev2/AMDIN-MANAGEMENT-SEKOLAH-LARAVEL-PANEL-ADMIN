<?php

namespace App\Filament\User\Resources\Kelas\RelationManagers;

use App\Filament\User\Resources\Siswas\SiswaResource;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswaRelationManager extends RelationManager
{
   
    protected static string $relationship = 'siswa';
    protected static ?string $title = 'Siswa';
    protected static string | BackedEnum | null $icon = 'heroicon-o-academic-cap';

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->width(100)
                    ->disk('public'),
                TextColumn::make('nisn')->label('NISN')
                    ->searchable(),
                TextColumn::make('nama_siswa')->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('jk')->label('Jenis Kelamin')
                    ->searchable(),
                TextColumn::make('agama')->label('Agama')
                    ->searchable(),
                TextColumn::make('tahun_masuk')->label('Angkatan')
                    ->searchable(),
            ])

            ->recordActions([
                ViewAction::make()->button()->color('success')
                    ->url(fn($record) => SiswaResource::getUrl('view', ['record' => $record->id])),
                Action::make('remove')
                    ->label('Keluarkan')
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription(fn($record)=>"keluarkan siswa {$record->nama_siswa} dari kelas ini?")
                    ->icon('heroicon-o-trash')
                    ->action(function ($record, $livewire) {
                        $record->update(['id_kelas' => null]);
                    }),
            ])

            ->headerActions([
                Action::make('import')->label('Import Data Siswa')->button()->color('success'),
                Action::make('add')->button()->color('primary')
                    ->label('Tambah Siswa')
                    ->modalWidth('sm')
                    ->modalIcon('heroicon-o-user-plus')
                    ->modalHeading('Tambah Siswa')
                    ->modalDescription('Pilih siswa yang akan ditambahkan ke kelas ini.')
                    ->schema([
                        Select::make('id_siswa')
                            ->native(false)
                            ->multiple()
                            ->label('Pilih Siswa')
                            ->options(
                                SiswaResource::getEloquentQuery()
                                    ->where('id_sekolah', auth()->user()->id_sekolah)
                                    ->whereNull('id_kelas')
                                    ->pluck('nama_siswa', 'id')
                            )
                    ])
                    ->action(function (array $data, $livewire) {
                        $kelas = $livewire->ownerRecord;

                        foreach ($data['id_siswa'] as $id_siswa) {
                            Siswa::where('id', $id_siswa)->update([
                                'id_kelas' => $kelas->id,
                            ]);
                        }
                    }),
            ]);
    }
}
