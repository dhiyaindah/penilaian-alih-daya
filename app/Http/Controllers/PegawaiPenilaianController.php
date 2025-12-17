<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pegawai;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;

class PegawaiPenilaianController extends Controller
{
    /* ===============================
    | HALAMAN SECTION
    |===============================*/
    public function kebersihan()
    {
        return $this->section('kebersihan');
    }

    public function taman()
    {
        return $this->section('taman');
    }

    public function keamanan()
    {
        return $this->section('keamanan');
    }

    public function sopir()
    {
        return $this->section('sopir');
    }

    private function section($section)
    {
        if (!in_array($section, ['kebersihan','taman','keamanan','sopir'])) {
            abort(404);
        }

        $pegawais = TimAlihDaya::where('jabatan', $section)->get();
        $penilai  = Pegawai::orderBy('nama')->get();
        $data     = session("penilaian.$section", []);

        return view("penilaian.$section", compact(
            'pegawais',
            'penilai',
            'section',
            'data'
        ));
    }

    /* ===============================
    | SIMPAN & FLOW
    |===============================*/
    public function store(Request $request, $section)
    {
        /* ===============================
        | 1. SIMPAN DATA PENILAI
        |===============================*/
        if ($request->filled('penilai_id')) {
            $penilai = Pegawai::find($request->penilai_id);

            session()->put('penilai', [
                'pegawai_id'  => $penilai->id,
                'penilai_nip' => $penilai->nip
            ]);
        }

        /* ===============================
        | 2. SIMPAN DATA SECTION
        |===============================*/
        session()->put("penilaian.$section", [
            'skor'     => $request->input('skor', []),
            'catatan' => $request->input('catatan', [])
        ]);

        /* ===============================
        | 3. FLOW SECTION (SAMA ADMIN)
        |===============================*/
        $flow = [
            'kebersihan' => ['prev' => null, 'next' => 'taman'],
            'taman'      => ['prev' => 'kebersihan', 'next' => 'keamanan'],
            'keamanan'   => ['prev' => 'taman', 'next' => 'sopir'],
            'sopir'      => ['prev' => 'keamanan', 'next' => null],
        ];

        /* ===============================
        | 4. PREV
        |===============================*/
        if ($request->action === 'prev') {
            return redirect()->route(
                'penilaian.' . $flow[$section]['prev']
            );
        }

        /* ===============================
        | 5. NEXT / SIMPAN FINAL
        |===============================*/
        if ($request->action === 'next') {

            // SECTION TERAKHIR
            if ($section === 'sopir') {

                $penilai   = session('penilai');
                $penilaian = session('penilaian');

                foreach ($penilaian as $sectionName => $data) {

                    if (!isset($data['skor'])) continue;

                    foreach ($data['skor'] as $alihDayaId => $skor) {
                        Penilaian::create([
                            'pegawai_id'   => $penilai['pegawai_id'],
                            'alih_daya_id' => $alihDayaId,
                            'skor'         => $skor,
                            'catatan'      => $data['catatan'][$alihDayaId] ?? null,
                        ]);
                    }
                }


            }



        }

        
    }
}
