<?php

namespace App\Traits;

use App\Models\Sekolah;

trait BelongsToSekolah
{
    /**
     * Relasi ke model Sekolah.
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
