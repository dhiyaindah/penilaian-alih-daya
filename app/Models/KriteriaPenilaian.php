<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaPenilaian extends Model
{
    protected $table = 'kriteria_penilaian';

    protected $fillable = [
        'nama',
        'keterangan',
        'skor_maks',
    ];
}
