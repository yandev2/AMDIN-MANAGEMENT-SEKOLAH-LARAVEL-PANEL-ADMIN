<?php

namespace App\Filament\User\Resources\Gurus\Tables;

use App\Models\Jabatan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GurusTable
{


    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->width(100)
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('guru.nip')
                    ->label('NIP')
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('guru', function ($guruQuery) use ($search) {
                            $guruQuery->where('nip', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('usia')
                    ->label('Usia')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state . ' Tahun')
                    ->badge(),
                TextColumn::make('guru.jabatan.nama_jabatan')
                    ->label('Jabatan'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('guru.status_dinas')
                    ->label('Dinas')

            ])
            ->filters([
                SelectFilter::make('status_dinas')
                    ->label('Status Dinas')
                    ->native(false)
                    ->options([
                        'dinas dalam' => 'Dinas Dalam',
                        'dinas luar' => 'Dinas Luar',
                    ])
                    ->query(function ($query, $data) {
                        $q = $data['value'];
                        if (blank($q)) return;

                        $query->whereHas('guru', function ($guruQuery) use ($q) {
                            $guruQuery->where('status_dinas', $q);
                        });
                    }),

                SelectFilter::make('status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'active' => 'active',
                        'inactive' => 'inactive',
                    ])
                    ->query(function ($query, $data) {
                        $q = $data['value'];
                        if (blank($q)) return;

                        $query->whereHas('guru', function ($guruQuery) use ($q) {
                            $guruQuery->where('status', $q);
                        });
                    }),


                SelectFilter::make('jabatan')
                    ->label('Jabatan')
                    ->native(false)
                    ->options(Jabatan::pluck('nama_jabatan', 'nama_jabatan'))
                    ->query(function ($query, $data) {
                        $q = $data['value'];
                        if (blank($q)) return;

                        $query->whereHas('guru.jabatan', function ($guruQuery) use ($q) {
                            $guruQuery->where('nama_jabatan', $q);
                        });
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

                        $query->whereRelation('guru', function ($q) use ($data) {
                            $q->whereRaw(
                                'EXTRACT(YEAR FROM AGE(current_date, tanggal_lahir)) = ?',
                                [$data['age']]
                            );
                        });
                    })
                    ->indicateUsing(function (array $data): ?string {
                        return $data['age'] == null ? null : 'Usia ' . $data['age'] . ' Tahun' ?? '';
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
                EditAction::make()->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
