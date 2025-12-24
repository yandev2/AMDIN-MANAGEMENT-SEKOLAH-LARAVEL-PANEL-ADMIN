<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'id_guru',
        'tingkat',
        'nama_kelas',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function presensi()
    {
        return $this->hasManyThrough(
            PresensiSiswa::class, // model target
            Siswa::class,          // model perantara
            'id_kelas',            // foreign key di tabel siswa
            'id_siswa',            // foreign key di tabel presensi_siswa
            'id',                  // local key di tabel kelas
            'id'                   // local key di tabel siswa
        )->orderByDesc('tanggal');
    }

    public function mapel()
    {
        return $this->hasMany(Mapel::class, 'id_kelas');
    }
}
