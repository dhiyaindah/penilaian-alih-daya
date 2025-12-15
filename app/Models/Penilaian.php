<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'alih_daya_id',
        'pegawai_id',
        'periode_id',
        'total_skor',
        'rekomendasi',
        'rekomendasi_lain',
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

    // Periode penilaian
    public function periode()
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_id');
    }

    // Detail skor per kriteria
    public function detail()
    {
        return $this->hasMany(DetailPenilaian::class, 'penilaian_id');
    }
}
