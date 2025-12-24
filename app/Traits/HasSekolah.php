<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasSekolah
{
    protected static function bootHasSekolah()
    {
        // 🔹 Auto isi id_sekolah saat create data
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->id_sekolah) {
                $model->id_sekolah = auth()->user()->id_sekolah;
            }
        });

        // 🔹 Global scope: filter data berdasarkan sekolah user
        static::addGlobalScope('sekolah', function (Builder $builder) {
            if (auth()->check()) {
                $userSekolah = auth()->user()->id_sekolah;

                // Kalau user punya id_sekolah (bukan super admin)
                if ($userSekolah) {
                    $builder->where($builder->getModel()->getTable() . '.id_sekolah', $userSekolah);
                }
            }
        });
    }
}
