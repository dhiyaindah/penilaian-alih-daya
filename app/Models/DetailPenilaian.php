<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenilaian extends Model
{
    protected $table = 'detail_penilaian';

    protected $fillable = [
        'penilaian_id',
        'kriteria_id',
        'skor',
        'catatan',
    ];

    // =====================
    //   RELASI
    // =====================

    // Relasi ke penilaian induk
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

    // Relasi ke kriteria
    public function kriteria()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'kriteria_id');
    }
}
