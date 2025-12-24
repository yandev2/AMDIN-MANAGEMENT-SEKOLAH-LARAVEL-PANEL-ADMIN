<?php

namespace App\Filament\User\Resources\Siswas\Pages;

use App\Filament\User\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Tambah Siswa';
    }

    public function afterCreate()
    {
        $data = $this->record;
        if ($data->foto == null) {
            $data->jk === 'L' ? $data->foto = 'siswa/default_lk.png' :  $data->foto = 'siswa/default_p.png';
        }
        $data->save();
    }
}
