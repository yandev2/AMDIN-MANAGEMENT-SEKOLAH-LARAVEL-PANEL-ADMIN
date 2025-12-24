<?php

namespace App\Filament\User\Resources\Kelas\Pages;

use App\Filament\User\Resources\Kelas\KelasResource;
use App\Models\Kelas;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

     protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->requiresConfirmation(Kelas::whereNot('id', $this->data['id'])->where('id_guru', $this->data['id_guru'])->exists())
                ->modalHeading(Kelas::whereNot('id', $this->data['id'])->where('id_guru', $this->data['id_guru'])->exists() == false ? null : 'Guru ini sudah memiliki kelas')
                ->modalDescription(Kelas::whereNot('id', $this->data['id'])->where('id_guru', $this->data['id_guru'])->exists() == false ? null : 'Apakah Anda yakin ingin mengganti kelas untuk guru ini?')
                ->action(function (array $data, Action $action) {
                    $exist = Kelas::whereNot('id', $this->data['id'])->where('id_guru', $this->data['id_guru']);
                    if ($exist->exists()) {
                        $exist->update(['id_guru' => null]);
                    }
                    $this->save();
                }),
            $this->getCancelFormAction(),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
