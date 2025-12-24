<?php

namespace App\Filament\User\Resources\Notifications\Pages;

use App\Filament\User\Resources\Notifications\NotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
