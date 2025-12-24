<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use BelongsToSekolah, HasSekolah;
    protected $fillable = [
        'id_sekolah',
        'id_kelas',
        'nis',
        'nisn',
        'nama_siswa',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'tahun_masuk',
        'foto',
        'nik',
        'no_kk',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function nokSiswa()
    {
        return $this->hasMany(NokSiswa::class, 'id_siswa');
    }

    public function presensiSiswa()
    {
        return $this->hasMany(PresensiSiswa::class, 'id_siswa');
    }

    public function getJumlahPresensiAttribute()
    {
        return $this->presensiSiswa();
    }

    public function tahunAjaranTerbaru()
    {
        return $this->presensiSiswa()
            ->orderByDesc('tanggal')
            ->value('tahun_ajaran');
    }
}
