<?php

namespace App\Filament\User\Resources\Jabatans\Pages;

use App\Filament\User\Resources\Jabatans\JabatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJabatans extends ListRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
