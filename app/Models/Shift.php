<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'nama_shift',
        'jam_masuk',
        'jam_keluar',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_shift');
    }
}
