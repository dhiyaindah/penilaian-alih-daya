<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Pegawai;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;

class PegawaiPenilaianController extends Controller
{
    public function index()
    {
        $pegawais = TimAlihDaya::where('jabatan', 'kebersihan')->get();

        // Ambil pegawai yang belum menilai (belum ada record di tabel penilaian)
        $penilai = Pegawai::whereDoesntHave('penilaian')->orderBy('nama')->get();
        $section = 'kebersihan';

        return view('penilaian.kebersihan', compact('pegawais', 'penilai', 'section'));
    }

    public function store(Request $request, $section)
    {
        // dd(
        //     'request', $request->penilai_id,
        //     'session', session('skor')
        // );

        if ($request->filled('penilai_id')) {
            session()->put('penilai.penilai_id', $request->penilai_id);
            session()->put( 'penilai.penilai_nip', Pegawai::find($request->penilai_id)?->nip );
        }

        // ===== VALIDASI: semua pegawai harus diisi skor =====
        // Ambil pegawai sesuai section
        // $pegawais = TimAlihDaya::where('jabatan', $section)->get(); // asumsi ada kolom 'bidang'
        // if ($request->action === 'next') {
        //     $missing = collect($pegawais)->pluck('id')->filter(fn($id) => !isset($skor[$id]))->toArray();
        //     if (!empty($missing)) {
        //         return back()
        //             ->withInput()
        //             ->with('error', '⚠️ Semua pegawai wajib diberi nilai sebelum lanjut.');
        //     }
        // }

        /* ===============================
        * 2. SIMPAN DATA SECTION
        * =============================== */
        session()->put("penilaian.$section", [
            'skor'    => $request->input('skor', []),
            'catatan'=> $request->input('catatan', []),
        ]);

        /* ===============================
        * 3. FLOW SECTION
        * =============================== */
        $flow = [
            'kebersihan' => ['prev' => null, 'next' => 'taman'],
            'taman'      => ['prev' => 'kebersihan', 'next' => 'keamanan'],
            'keamanan'   => ['prev' => 'taman', 'next' => 'sopir'],
            'sopir'      => ['prev' => 'keamanan', 'next' => null],
        ];

        $action = $request->action;

        /* ===============================
        * 4. PREV
        * =============================== */
        if ($action === 'prev') {
            return redirect()->route(
                'public.penilaian.section',
                $flow[$section]['prev']
            );
        }

        /* ===============================
        * 5. NEXT
        * =============================== */
        if ($action === 'next') {

            // 🔚 SECTION TERAKHIR
            if ($section === 'sopir') {

                $penilai   = session('penilai');
                $penilaian = session('penilaian');

                foreach ($penilaian as $sectionName => $data) {

                    if (!isset($data['skor'])) continue;

                    foreach ($data['skor'] as $alihDayaId => $skor) {
                        Penilaian::create([
                            'pegawai_id'   => $penilai['penilai_id'],
                            'alih_daya_id' => $alihDayaId,
                            'skor'         => $skor,
                            'catatan'      => $data['catatan'][$alihDayaId] ?? null,
                        ]);
                    }
                }

                // ambil nama penilai
                $penilaiData = Pegawai::find($penilai['penilai_id']);
                session()->flash('nama_penilai', $penilaiData?->nama);

                // bersihkan session utama
                session()->forget(['penilai', 'penilaian']);

                return redirect()->route('public.penilaian.terimakasih');
            }

            // LANJUT SECTION
            return redirect()->route(
                'public.penilaian.section',
                $flow[$section]['next']
            );
        }
    }

    public function show($section)
    {
        $data = session("penilaian.$section", []);
        $pegawais = TimAlihDaya::where('jabatan', $section)->get();
        $penilai = Pegawai::orderBy('nama')->get();

        return view("penilaian.$section", [
            'data' => $data,
            'pegawais' => $pegawais,
            'penilai' => $penilai,
        ]);
    }

    public function terimakasih(Request $request)
    {   
        return view('penilaian.terimakasih');
    }
}
