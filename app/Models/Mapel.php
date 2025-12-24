<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'id_kelas',
        'id_guru',
        'nama_mapel',
        'jam_masuk',
        'jam_keluar',
        'deskripsi',
        'hari',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

      public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
}
