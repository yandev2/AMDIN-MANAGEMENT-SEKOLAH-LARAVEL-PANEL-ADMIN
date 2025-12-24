<?php

namespace App\Filament\User\Resources\Kelas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->rowIndex(),
                TextColumn::make('nama_kelas')
                    ->searchable(),
                TextColumn::make('guru.user.name')
                    ->label('Wali kelas')
                    ->searchable(),
                TextColumn::make('deskripsi'),
            ])
            ->filters([
                //
            ])
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
