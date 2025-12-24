<?php

namespace App\Filament\User\Resources\PresensiSiswas\Pages;

use App\Filament\User\Resources\PresensiSiswas\PresensiSiswaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPresensiSiswa extends ViewRecord
{
    protected static string $resource = PresensiSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
