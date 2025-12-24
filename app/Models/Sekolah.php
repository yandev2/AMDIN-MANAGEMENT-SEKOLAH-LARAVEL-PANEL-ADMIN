<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'level',
        'alamat',
        'kota',
        'provinsi',
        'no_tlp',
        'email',
        'website',
        'logo_path',
        'location',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_sekolah');
    }

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_sekolah');
    }

    public function kepalaSekolah()
    {
        return $this->hasMany(Guru::class, 'id_sekolah')
            ->with(['user', 'jabatan']) // <-- ini biar relasi ikut di-load
            ->whereHas('jabatan', function ($query) {
                $query->where('nama_jabatan', 'kepala sekolah');
            });
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_sekolah');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_sekolah');
    }

    public function shift()
    {
        return $this->hasMany(Shift::class, 'id_sekolah');
    }

    public function jabatanGuru()
    {
        return $this->hasMany(Jabatan::class, 'id_sekolah');
    }

    public function mapel()
    {
        return $this->hasMany(Mapel::class, 'id_sekolah');
    }

    protected static function booted()
    {
        // Hapus foto lama jika diganti
        static::updating(function ($model) {
            if ($model->isDirty('logo_path')) {
                $oldFile = $model->getOriginal('logo_path');

                if ($oldFile && \Storage::disk('public')->exists($oldFile)) {
                    \Storage::disk('public')->delete($oldFile);
                }
            }
        });

        // Hapus foto jika crew dihapus
        static::deleted(function ($model) {
            if ($model->logo_path && \Storage::disk('public')->exists($model->logo_path)) {
                \Storage::disk('public')->delete($model->logo_path);
            }
        });
    }
}
