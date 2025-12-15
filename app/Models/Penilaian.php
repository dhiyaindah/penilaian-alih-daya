<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'alih_daya_id',
        'pegawai_id',
        'skor',
        'catatan',
    ];

    // =====================
    //   RELASI
    // =====================

    // Yang dinilai
    public function alihDaya()
    {
        return $this->belongsTo(TimAlihDaya::class, 'alih_daya_id');
    }

    // Pegawai yang menilai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
