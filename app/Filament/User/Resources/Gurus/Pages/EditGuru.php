<?php

namespace App\Filament\User\Resources\Gurus\Pages;

use App\Filament\User\Resources\Gurus\GuruResource;
use App\Models\Guru;
use App\Models\Jabatan;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGuru extends EditRecord
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function beforeSave()
    {

        $form =  $this->form->getRawState();
        $namaJabatan = Jabatan::where('id', $form['guru']['id_jabatan'])->value('nama_jabatan');
        $idSekolah = auth()->user()->id_sekolah;

        if ($namaJabatan === 'kepala sekolah') {
            $existingKepala = Guru::whereHas('jabatan', function ($q) {
                $q->where('nama_jabatan', 'kepala sekolah');
            })
                ->where('id_sekolah', $idSekolah)
                ->first();

            if ($existingKepala) {
                $existingUser = $existingKepala->user;
                $existingUser->syncRoles('guru');
                $existingKepala->update(['id_jabatan' => null]);
            }
        }
    }

    public function afterSave()
    {
        $user = $this->record;
        if ($user->foto == null) {
            $user->guru->jk === 'L' ? $user->foto = 'guru/default_sys_l.jpg' :  $user->foto = 'guru/default_sys.jpg';
        }

        if ($user->guru->jabatan->nama_jabatan === 'kepala sekolah') {
            $user->syncRoles('kepala_sekolah');
        } else {
            $user->syncRoles('guru');
        }
    }
}
