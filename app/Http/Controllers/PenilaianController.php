<?php

namespace App\Http\Controllers;

use App\Exports\PenilaianPerPegawaiExport;
use App\Models\DetailPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\Penilaian;
use App\Models\Pegawai;
use App\Models\PeriodePenilaian;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianController extends Controller
{
    public function index()
    {
        $pegawais = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        
        // Ambil pegawai yang belum menilai (belum ada record di tabel penilaian)
        $penilai = Pegawai::whereDoesntHave('penilaian')->orderBy('nama')->get();

        return view('admin.penilaian.kebersihan', compact('pegawais', 'penilai'));
    }

    public function index2()
    {
        $kebersihan = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        $taman = TimAlihDaya::where('jabatan', 'taman')->get();
        $keamanan = TimAlihDaya::where('jabatan', 'keamanan')->get();
        $sopir = TimAlihDaya::where('jabatan', 'sopir')->get();

        return view('admin.penilaian.index2', compact('kebersihan', 'taman', 'keamanan', 'sopir'));
    }

    public function index3()
    {
        $kebersihan = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        $taman = TimAlihDaya::where('jabatan', 'taman')->get();
        $keamanan = TimAlihDaya::where('jabatan', 'keamanan')->get();
        $sopir = TimAlihDaya::where('jabatan', 'sopir')->get();

        return view('admin.penilaian.index3', compact('kebersihan', 'taman', 'keamanan', 'sopir'));
    }

    public function index4()
    {
        $kebersihan = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        $taman = TimAlihDaya::where('jabatan', 'taman')->get();
        $keamanan = TimAlihDaya::where('jabatan', 'keamanan')->get();
        $sopir = TimAlihDaya::where('jabatan', 'sopir')->get();

        return view('admin.penilaian.index4', compact('kebersihan', 'taman', 'keamanan', 'sopir'));
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
        //     'request', $request->pegawai_id,
        //     'session', session('penilai')
        // );

        if ($request->filled('pegawai_id')) { 
            session()->put('penilai.pegawai_id', $request->pegawai_id); 
            session()->put( 'penilai.penilai_nip', Pegawai::find($request->pegawai_id)?->nip ); 
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
                            'pegawai_id'   => $penilai['pegawai_id'],
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

    public function rekap()
    {
        $rekap = DB::table('penilaian')
            ->join('tim_alih_daya', 'penilaian.alih_daya_id', '=', 'tim_alih_daya.id')
            ->select(
                'tim_alih_daya.id',
                'tim_alih_daya.nama',
                'tim_alih_daya.jabatan',
                'tim_alih_daya.foto',
                DB::raw('ROUND(AVG(penilaian.skor), 2) as rata_rata')
            )
            ->groupBy(
                'tim_alih_daya.id',
                'tim_alih_daya.nama',
                'tim_alih_daya.jabatan',
                'tim_alih_daya.foto'
            )
            ->orderBy('tim_alih_daya.jabatan')
            ->orderBy('tim_alih_daya.nama')
            ->paginate(50); 

        return view('admin.penilaian.rekap', compact('rekap'));
    }

    public function detail(Request $request)
    {
        // Query dasar
        $query = DB::table('penilaian')
            ->join('tim_alih_daya', 'penilaian.alih_daya_id', '=', 'tim_alih_daya.id')
            ->join('pegawai as penilai', 'penilaian.pegawai_id', '=', 'penilai.id')
            ->select(
                'tim_alih_daya.id as alih_daya_id',
                'tim_alih_daya.nama as nama_pegawai',
                'tim_alih_daya.jabatan',
                'tim_alih_daya.foto',
                'penilai.id as pegawai_id',
                'penilai.nama as nama_penilai',
                'penilaian.created_at',
                'penilaian.skor',
                'penilaian.catatan'
            );

        // Filter bidang
        if ($request->filled('bidang')) {
            $query->where('tim_alih_daya.jabatan', $request->bidang);
        }

        // Filter pegawai alih daya
        if ($request->filled('alih_daya_id')) {
            $query->where('penilaian.alih_daya_id', $request->alih_daya_id);
        }

        // Filter penilai
        if ($request->filled('pegawai_id')) {
            $query->where('penilaian.pegawai_id', $request->pegawai_id);
        }

        // Filter periode (bulan/tahun)
        if ($request->filled('periode')) {
            $query->whereYear('penilaian.created_at', substr($request->periode, 0, 4))
                ->whereMonth('penilaian.created_at', substr($request->periode, 5, 2));
        }

        // Handle grouping
        if ($request->filled('group_by')) {
            $penilaian = $query->orderBy('penilaian.created_at', 'desc')->get();
            
            // Group data berdasarkan pilihan
            $groupedData = collect();
            
            switch ($request->group_by) {
                case 'alih_daya':
                    $groupedData = $penilaian->groupBy('alih_daya_id');
                    break;
                    
                case 'bidang':
                    $groupedData = $penilaian->groupBy('jabatan');
                    break;
                    
                case 'penilai':
                    $groupedData = $penilaian->groupBy('pegawai_id');
                    break;
            }
            
            $penilaian = $query->paginate(50); // Untuk pagination tetap
        } else {
            $penilaian = $query->orderBy('penilaian.created_at', 'desc')->paginate(50);
            $groupedData = collect();
        }

        // Data untuk dropdown filter
        $pegawaiList = DB::table('tim_alih_daya')
            ->select('id', 'nama', 'jabatan')
            ->orderBy('nama')
            ->get();
            
        $penilaiList = DB::table('pegawai')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('admin.penilaian.detail', compact(
            'penilaian', 
            'groupedData', 
            'pegawaiList', 
            'penilaiList'
        ));
    }

    public function exportExcel()
    {
        $filename = 'penilaian-per-pegawai-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new PenilaianPerPegawaiExport(), $filename);
    }
}
