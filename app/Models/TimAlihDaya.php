<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimAlihDaya extends Model
{
    protected $table = 'tim_alih_daya';

    protected $fillable = [
        'nama',
        'jabatan',
        'status',
        'foto',
    ];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'alih_daya_id');
    }

    public function penilaianAktif($periode_id)
    {
        return $this->hasOne(Penilaian::class, 'alih_daya_id')
                    ->where('periode_id', $periode_id);
    }
}
