<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('sekolah.nama_sekolah')
                    ->label('Sekolah')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y'),
                TextColumn::make('sekolah.status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state) {
                        return $state === 'active' ? 'success' : 'danger';
                    }),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->button()->color('success'),
                EditAction::make()->button()->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
