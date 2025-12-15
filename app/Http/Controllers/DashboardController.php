<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Ambil kehadiran beserta data pegawai
        $kehadiranHariIni = $statuses = Pegawai::all();

        // Hitung total status
        $statuses = Pegawai::all();

        $totalPegawai = Pegawai::count();

        return view('admin.dashboard.main', compact(
            'statuses',
            'totalPegawai',
            'kehadiranHariIni'
        ));
    }
}
