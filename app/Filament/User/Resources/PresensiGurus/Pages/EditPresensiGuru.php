<?php

namespace App\Filament\User\Resources\PresensiGurus\Pages;

use App\Filament\User\Resources\PresensiGurus\PresensiGuruResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPresensiGuru extends EditRecord
{
    protected static string $resource = PresensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
        
        ];
    }
}
