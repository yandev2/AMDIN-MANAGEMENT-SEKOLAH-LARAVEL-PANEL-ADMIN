<?php

namespace App\Filament\User\Resources\Gurus\Pages;

use App\Filament\User\Resources\Gurus\GuruResource;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateGuru extends CreateRecord
{
  protected static string $resource = GuruResource::class;

  public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
  {
    return 'Tambah Guru';
  }


  public function beforeCreate()
  {
    $form =  $this->form->getRawState();
    $idSekolah = auth()->user()->id_sekolah;
    $namaJabatan = Jabatan::where('id', $form['guru']['id_jabatan'])->value('nama_jabatan');

    if ($namaJabatan === 'kepala sekolah') {
      $existingKepala = Guru::whereHas('jabatan', function ($q) {
        $q->where('nama_jabatan', 'kepala sekolah');
      })
        ->where('id_sekolah', $idSekolah)
        ->first();

      if ($existingKepala) {
        $existingUser = $existingKepala->user;
        $existingUser->syncRoles(['guru']);
        $existingKepala->update(['id_jabatan' => null]);
      }
    }
  }

  public function afterCreate()
  {
    $user = $this->record;
    $jabatan = $user->guru?->jabatan?->nama_jabatan;
    $user->id_sekolah = auth()->user()->id_sekolah;

    if ($user->foto == null) {
      $user->guru->jk === 'L' ? $user->foto = 'guru/default_sys_l.jpg' :  $user->foto = 'guru/default_sys.jpg';
    }
    $user->save();


    if ($jabatan !== 'kepala sekolah') {
      $user->assignRole('guru');
    }

    if ($jabatan === 'kepala sekolah') {
      $user->assignRole('kepala_sekolah');
    }
  }
}
