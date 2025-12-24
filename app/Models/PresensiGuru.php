<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'id_guru',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'absen_masuk',
        'lokasi_masuk',
        'absen_keluar',
        'lokasi_keluar',
        'durasi_kerja',
        'dokumen',
        'keterangan',
        'face',
        'status',
    ];

    public function getGuruNameAttribute()
    {
        return $this->guru?->name ?? '-';
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
}
