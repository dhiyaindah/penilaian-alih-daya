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
        $kebersihan = TimAlihDaya::where('jabatan', 'kebersihan')->get();
        $taman = TimAlihDaya::where('jabatan', 'taman')->get();
        $keamanan = TimAlihDaya::where('jabatan', 'keamanan')->get();
        $sopir = TimAlihDaya::where('jabatan', 'sopir')->get();

        return view('admin.penilaian.index', compact('kebersihan', 'taman', 'keamanan', 'sopir'));
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

    public function store(Request $request)
    {
        // Cari periode aktif
        $periodeAktif = PeriodePenilaian::where('status', 'aktif')->first();

        if (!$periodeAktif) {
            return back()->withErrors(['msg' => 'Tidak ada periode penilaian yang aktif.'])->withInput();
        }

        // ------------------------------
        // 1. VALIDASI INPUT
        // ------------------------------
        $validated = $request->validate([
            'alih_daya_id'     => 'required|exists:tim_alih_daya,id',
            'penilai_id'       => 'required|exists:users,id',
            'kriteria_id'      => 'required|array',
            'kriteria_id.*'    => 'exists:kriteria_penilaian,id',

            'nilai'            => 'required|array',
            'nilai.*'          => 'required|integer|min:0',

            'catatan'          => 'nullable|array',
            'catatan.*'        => 'nullable|string',

            'rekomendasi'      => 'required|string',
            'rekomendasi_lain_teks' => 'nullable|string'
        ]);


        // ------------------------------
        // 2. HITUNG TOTAL SKOR MAKS DARI DB
        // ------------------------------
        $totalSkorMaks = KriteriaPenilaian::whereIn('id', $validated['kriteria_id'])
                            ->sum('skor_maks');

        // ------------------------------
        // 3. HITUNG TOTAL SKOR DIPEROLEH & NILAI AKHIR (0–100)
        // ------------------------------
        $totalSkorDiperoleh = array_sum($validated['nilai']);

        $nilaiAkhir = ($totalSkorDiperoleh / $totalSkorMaks) * 100;

        // ------------------------------
        // 4. PROSES REKOMENDASI
        // ------------------------------
        if ($validated['rekomendasi'] === 'rekomendasi_lain') {
            $rekomendasi = 'lainnya';
            $rekomendasiLain = $validated['rekomendasi_lain_teks'];
        } else {
            $map = [
                'dilanjutkan' => 'dilanjutkan',
                'perlu_pembinaan' => 'perlu pembinaan',
                'penggantian_tenaga' => 'penggantian tenaga',
                'evaluasi_kontrak' => 'evaluasi kontrak dengan vendor',
            ];

            $rekomendasi = $map[$validated['rekomendasi']] ?? 'dilanjutkan';
            $rekomendasiLain = null;
        }

        // ------------------------------
        // 5. SIMPAN PENILAIAN (Nilai Akhir)
        // ------------------------------
        $penilaian = Penilaian::create([
            'alih_daya_id'   => $validated['alih_daya_id'],
            'pegawai_id'     => $validated['penilai_id'],
            'periode_id'     => $periodeAktif->id,
            'total_skor'     => round($nilaiAkhir, 2), // simpan 2 desimal (contoh: 87.50)
            'rekomendasi'    => $rekomendasi,
            'rekomendasi_lain' => $rekomendasiLain,
        ]);


        // ------------------------------
        // 6. SIMPAN DETAIL PENILAIAN
        // ------------------------------
        foreach ($validated['kriteria_id'] as $i => $kriteriaId) {
            DetailPenilaian::create([
                'penilaian_id' => $penilaian->id,
                'kriteria_id'  => $kriteriaId,
                'skor'         => $validated['nilai'][$i],
                'catatan'      => $validated['catatan'][$i] ?? null,
            ]);
        }

        return redirect()
            ->route('penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan!');
    }

    public function show($id)
    {
        // $penilaian = Penilaian::with('pegawai', 'penilai')->findOrFail($id);
        // return view('admin.penilaian.show', compact('penilaian'));
    }

    public function destroy($id)
    {
        Penilaian::findOrFail($id)->delete();
        return back()->with('success', 'penilaian berhasil dihapus.');
    }
}
