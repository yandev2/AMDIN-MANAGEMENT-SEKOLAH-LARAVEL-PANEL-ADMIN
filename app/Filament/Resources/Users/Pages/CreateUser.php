<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    public function afterCreate(): void
    {
        $user = $this->record;
        $user->assignRole('operator');

        $sekolah =  $user->sekolah()->create([
            'nama_sekolah' => $this->data['nama_sekolah'],
            'level' =>  $this->data['level'],
            'status' =>  $this->data['status'],
        ]);

        $user->id_sekolah = $sekolah->id;
        $user->save();
    }
}
