<?php

namespace App\Filament\User\Resources\Gurus\Pages;

use App\Filament\User\Resources\Gurus\GuruResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGuru extends ViewRecord
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
