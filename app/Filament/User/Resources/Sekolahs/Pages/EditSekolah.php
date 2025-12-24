<?php

namespace App\Filament\User\Resources\Sekolahs\Pages;

use App\Filament\User\Resources\Sekolahs\SekolahResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSekolah extends EditRecord
{
    protected static string $resource = SekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}
