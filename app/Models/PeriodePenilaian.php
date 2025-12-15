<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodePenilaian extends Model
{
    protected $table = 'periode_penilaian';

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
    ];
}
