<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\Penilaian;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $pegawais = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        $taman = TimAlihDaya::where('jabatan', 'taman')->get();
        $keamanan = TimAlihDaya::where('jabatan', 'keamanan')->get();
        $sopir = TimAlihDaya::where('jabatan', 'sopir')->get();
        $penilai = Pegawai::orderBy('nama')->get();

        return view('admin.penilaian.kebersihan', compact('pegawais', 'taman', 'keamanan', 'sopir', 'penilai'));
    }

    public function create($id)
    {
        $alih_daya = TimAlihDaya::findOrFail($id);
        $kriterias = KriteriaPenilaian::all();
        $totalSkor = $kriterias->sum('skor_maks');
        
        return view('admin.penilaian.create', compact('alih_daya', 'kriterias', 'totalSkor'));
    }

    public function store(Request $request, $section)
    {
        // dd(
        //     'request', $request->penilai_id,
        //     'session', session('penilai')
        // );

        if ($request->filled('penilai_id')) { 
            session()->put('penilai.penilai_id', $request->penilai_id); 
            session()->put( 'penilai.penilai_nip', Pegawai::find($request->penilai_id)?->nip ); 
        }

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
                'penilaian.section',
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

                // BERSIHKAN SESSION
                session()->forget(['penilai', 'penilaian']);

                return redirect()
                    ->route('penilaian.index')
                    ->with('success', 'Penilaian berhasil disimpan!');
            }

            // LANJUT SECTION
            return redirect()->route(
                'penilaian.section',
                $flow[$section]['next']
            );
        }
    }

    public function show($section)
    {
        $data = session("penilaian.$section", []);
        $pegawais = TimAlihDaya::where('jabatan', $section)->get();
        $penilai = Pegawai::orderBy('nama')->get();

        return view("admin.penilaian.$section", [
            'data' => $data,
            'pegawais' => $pegawais,
            'penilai' => $penilai,
        ]);
    }

    public function destroy($id)
    {
        Penilaian::findOrFail($id)->delete();
        return back()->with('success', 'penilaian berhasil dihapus.');
    }
}
