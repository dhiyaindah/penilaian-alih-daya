<?php

namespace App\Exports;

use App\Models\TimAlihDaya;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PenilaianPerPegawaiExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        // Tambahkan sheet ringkasan/rekap
        $sheets[] = new RekapSheet();
        
        // Ambil semua pegawai alih daya
        $pegawaiList = TimAlihDaya::orderBy('jabatan')
            ->orderBy('nama')
            ->get();
        
        foreach ($pegawaiList as $pegawai) {
            // Buat sheet untuk setiap pegawai
            $sheets[] = new PenilaianPegawaiSheet($pegawai);
        }
        
        return $sheets;
    }
}