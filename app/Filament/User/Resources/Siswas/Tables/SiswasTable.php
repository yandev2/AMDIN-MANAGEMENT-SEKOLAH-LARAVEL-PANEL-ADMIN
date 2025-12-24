<?php

namespace App\Filament\User\Resources\Siswas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->searchable(),
                TextColumn::make('usia')
                    ->label('Usia')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state . ' Tahun')
                    ->badge(),
                TextColumn::make('nama_siswa')
                    ->searchable(),
                TextColumn::make('jk')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('tahun_masuk')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'lulus' => 'danger',
                        'aktif' => 'success',
                        'skor' => 'warning'
                    })
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->native(false)
                    ->relationShip('kelas', 'nama_kelas'),
                SelectFilter::make('tahun_masuk')
                    ->label('Tahun Masuk')
                    ->native(false)
                    ->options(function () {
                        $years = [];
                        for ($i = date('Y'); $i >= 1970; $i--) {
                            $years[$i] = $i;
                        }
                        return $years;
                    }),
                Filter::make('usia')
                    ->label('Usia')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('age')
                            ->suffix('Tahun')
                            ->label('Usia')
                            ->numeric()
                            ->placeholder('Enter age'),
                    ])
                    ->query(function ($query, array $data) {
                        if (empty($data['age'])) {
                            return;
                        }
                        $query->whereRaw(
                            'EXTRACT(YEAR FROM AGE(current_date, tanggal_lahir)) = ?',
                            [$data['age']]
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return $data['age'] == null ? null : 'Usia ' . $data['age'] . ' Tahun' ?? '';
                    }),

                SelectFilter::make('jk')
                    ->label('Jenis Kelamin')
                    ->native(false)
                    ->options([
                        'L' => 'Laki Laki',
                        'P' => 'Perempuan',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'lulus' => 'Lulus',
                        'aktif' => 'Aktif',
                        'skor' => 'Skor',
                    ])
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
                EditAction::make()->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
