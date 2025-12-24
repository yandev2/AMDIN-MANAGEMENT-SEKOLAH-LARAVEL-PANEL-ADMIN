<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class NokSiswa extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'id_siswa',
        'hubungan',
        'nama',
        'no_tlp',
    ];

     public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
