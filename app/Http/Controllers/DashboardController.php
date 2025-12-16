<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Penilaian;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pegawai ASN (penilai)
        $totalPegawai = Pegawai::count();

        // Total pegawai alih daya
        $totalAlihDaya = TimAlihDaya::count();

        // Pegawai ASN yang SUDAH menilai (distinct)
        $sudahMenilai = Penilaian::distinct('pegawai_id')->count('penilai_id');

        // Pegawai ASN yang BELUM menilai
        $belumMenilai = $totalPegawai - $sudahMenilai;

        // Persentase (opsional, sekarang masuk akal)
        $persen = $totalPegawai > 0
            ? round(($sudahMenilai / $totalPegawai) * 100)
            : 0;

        return view('admin.dashboard.main', compact(
            'totalPegawai',
            'totalAlihDaya',
            'sudahMenilai',
            'belumMenilai',
            'persen'
        ));
    }
}
