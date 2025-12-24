<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'nama_jabatan',
        'gaji',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_jabatan');
    }
}
