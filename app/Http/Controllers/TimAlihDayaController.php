<?php

namespace App\Http\Controllers;

use App\Imports\TimAlihDayaImport;
use App\Models\TimAlihDaya;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TimAlihDayaController extends Controller
{
    public function index()
    {
        $alih_dayas = TimAlihDaya::where('status', 'aktif')
            ->orderBy('nama')
            ->paginate(20); 
        return view('admin.alih_daya.index', compact('alih_dayas'));
    }

    public function create()
    {
        return view('admin.alih_daya.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240', // max 10MB
        ]);

        $fotoPath = null;

        // Jika ada file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            // Buat nama file: nip_nama.ext
            $namaFile = time() . '-' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();

            // Simpan di folder storage/app/public/pegawai
            $fotoPath = $file->storeAs('pegawai', $namaFile, 'public');
        }

        // Simpan data ke database
        TimAlihDaya::create([
            'nama'   => $request->nama,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'foto'   => $fotoPath,
        ]);

        return redirect()->route('alih_daya.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(TimAlihDaya $alih_daya) {
        return view('admin.alih_daya.edit', compact('alih_daya'));
    }

    public function update(Request $request, TimAlihDaya $alih_daya)
    {
        
        // Validasi input
        $validated = $request->validate([
            'nama'             => 'required|string|max:255',
            'status'           => 'required|in:aktif,nonaktif',
            'jabatan'          => 'required|string|max:255',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama jika ada
            if ($alih_daya->foto && Storage::disk('public')->exists($alih_daya->foto)) {
                Storage::disk('public')->delete($alih_daya->foto);
            }

            // Upload foto baru
            $file = $request->file('foto');

            $filename = time() . '-' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('alih_daya', $filename, 'public');

            // Set data foto baru
            $validated['foto'] = $path;
        }

        // Update data alih_daya
        $alih_daya->update($validated);

        // Ambil page dari request
        $page = $request->input('page', 1);
        
        return redirect()->route('alih_daya.index', ['page' => $page])
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function import(Request $request)
    {
        try{
            $request->validate([
                'file' => 'required|mimes:xlsx,xls'
            ]);

            Excel::import(new TimAlihDayaImport, $request->file('file'));

            return redirect()
                ->route('alih_daya.index')
                ->with('success', 'Data alih daya berhasil diimpor!');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            return back()->with('error', 'Ada kesalahan pada data Excel. Cek kembali format dan datanya.');
        }
        catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
