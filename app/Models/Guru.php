<?php

namespace App\Models;

use App\Traits\BelongsToSekolah;
use App\Traits\HasSekolah;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use BelongsToSekolah, HasSekolah;

    protected $fillable = [
        'id_sekolah',
        'id_shift',
        'id_jabatan',
        'id_user',
        'nip',
        'jk',
        'alamat',
        'status_dinas',
        'status',
        'no_hp',
        'agama',
        'pendidikan_terakhir',
        'tempat_lahir',
        'tanggal_lahir',
        'face_id',
        'auth_token',
        'id_device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function presensiGuru()
    {
        return $this->hasMany(PresensiGuru::class, 'id_guru');
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru');
    }
}
