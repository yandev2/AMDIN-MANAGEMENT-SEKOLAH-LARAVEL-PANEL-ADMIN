<?php

namespace App\Filament\User\Resources\PresensiSiswas\Pages;

use App\Filament\User\Resources\PresensiSiswas\PresensiSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPresensiSiswa extends EditRecord
{
    protected static string $resource = PresensiSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable{
        return "Edit Presensi Siswa {$this->record->siswa->nama_siswa}";
    }
}
