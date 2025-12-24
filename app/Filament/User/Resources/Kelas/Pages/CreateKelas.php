<?php

namespace App\Filament\User\Resources\Kelas\Pages;

use App\Filament\User\Resources\Kelas\KelasResource;
use App\Models\Guru;
use App\Models\Kelas;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateKelas extends CreateRecord
{
    protected static string $resource = KelasResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->requiresConfirmation(Kelas::where('id_guru', $this->data['id_guru'])->exists())
                ->modalHeading(Kelas::where('id_guru', $this->data['id_guru'])->exists() == false ? null : 'Guru ini sudah memiliki kelas')
                ->modalDescription(Kelas::where('id_guru', $this->data['id_guru'])->exists() == false ? null : 'Apakah Anda yakin ingin mengganti kelas untuk guru ini?')
                ->action(function (array $data, Action $action) {
                    $exist = Kelas::where('id_guru', $this->data['id_guru']);
                    if ($exist->exists()) {
                        $exist->update(['id_guru' => null]);
                    }
                    $this->create();
                }),
            $this->getCancelFormAction(),
        ];
    }
}
