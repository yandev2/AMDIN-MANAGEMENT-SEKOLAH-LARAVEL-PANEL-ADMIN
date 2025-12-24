<?php

namespace App\Filament\User\Resources\PresensiGurus\Pages;

use App\Filament\User\Resources\Gurus\GuruResource;
use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPresensiGuru extends ViewRecord
{
    protected static string $resource = PresensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('View Detail Guru')
                ->icon('heroicon-o-eye')
                ->url(fn($record) => GuruResource::getUrl('view', ['record' => $record->guru->user->id]))
        ];
    }
}
