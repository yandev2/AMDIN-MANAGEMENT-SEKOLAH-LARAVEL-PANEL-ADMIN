<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\BelongsToSekolah;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'id_sekolah',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user');
    }

    protected static function booted()
    {
        //
        static::updating(function ($model) {
            if ($model->isDirty('foto')) {
                $oldFile = $model->getOriginal('foto');

                if ($oldFile && \Storage::disk('public')->exists($oldFile)) {
                    if (!in_array($oldFile, ['guru/default_sys_l.jpg', 'guru/default_sys.jpg'])) {
                        \Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        });

        // Hapus foto jika crew dihapus
        static::deleted(function ($model) {
            if ($model->foto && \Storage::disk('public')->exists($model->foto)) {
                if (!in_array($model->foto, ['guru/default_sys_l.jpg', 'guru/default_sys.jpg'])) {
                    \Storage::disk('public')->delete($model->logo_path);
                }
            }
        });
    }
}
