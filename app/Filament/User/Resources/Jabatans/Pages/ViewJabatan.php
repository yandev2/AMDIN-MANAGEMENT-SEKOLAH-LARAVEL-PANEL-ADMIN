<?php

namespace App\Filament\User\Resources\Jabatans\Pages;

use App\Filament\User\Resources\Jabatans\JabatanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewJabatan extends ViewRecord
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "View Jabatan {$this->record->nama_jabatan}";
    }
}
