<?php

namespace App\Filament\User\Resources\Kelas\Pages;

use App\Filament\User\Resources\Kelas\KelasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Tabs\Tab;

class ViewKelas extends ViewRecord
{
    protected static string $resource = KelasResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return "Kelas {$this->record->nama_kelas}";
    }
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
