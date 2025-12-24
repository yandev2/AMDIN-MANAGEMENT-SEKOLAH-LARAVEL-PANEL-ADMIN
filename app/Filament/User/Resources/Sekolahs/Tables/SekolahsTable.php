<?php

namespace App\Filament\User\Resources\Sekolahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_sekolah')
                    ->searchable(),
                TextColumn::make('npsn')
                    ->searchable(),
                TextColumn::make('level')
                    ->searchable(),
                TextColumn::make('kota')
                    ->searchable(),
                TextColumn::make('provinsi')
                    ->searchable(),
                TextColumn::make('website')
                    ->searchable(),
                ImageColumn::make('logo_path')
                    ->disk('public')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color('success')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->button()->color('success'),
                EditAction::make()->button()->color('info'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                
                ]),
            ]);
    }
}
