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
        // Total pegawai ASN AKTIF (penilai)
        $totalPegawai = Pegawai::where('status', 'aktif')->count();

        // Total pegawai alih daya AKTIF
        $totalAlihDaya = TimAlihDaya::where('status', 'aktif')->count();

        // Pegawai ASN AKTIF yang SUDAH menilai (distinct)
        $sudahMenilai = Penilaian::distinct('pegawai_id')->count('penilai_id');

        // Pegawai ASN AKTIF yang BELUM menilai
        $belumMenilai = $totalPegawai - $sudahMenilai;

        // Persentase
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
