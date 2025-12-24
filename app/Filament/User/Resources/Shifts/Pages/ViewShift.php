<?php

namespace App\Filament\User\Resources\Shifts\Pages;

use App\Filament\User\Resources\Shifts\ShiftResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewShift extends ViewRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "View Shift {$this->record->nama_shift}";
    }
}
