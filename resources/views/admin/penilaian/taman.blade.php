@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Penilaian Kinerja Pegawai Alih Daya
</li>
@endsection

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-center gap-4 flex-wrap">

            <img src="{{ asset('admin/Logo-Kemdikbud.png') }}"
                 alt="Logo"
                 style="height:90px;">

            <div class="text-center">
                <h6 class="fw-bold mb-1 text-uppercase text-secondary">
                    Kementerian Pendidikan Dasar dan Menengah
                </h6>
                <h6 class="fw-bold mb-2 text-uppercase text-secondary">
                    Republik Indonesia
                </h6>

                <h3 class="fw-bold text-primary mb-2">
                    Penilaian Alih Daya Tahun 2025
                </h3>

                <div class="fw-semibold">
                    Badan Pengembangan dan Pembinaan Bahasa
                </div>
                <div class="text-muted">
                    Balai Bahasa Provinsi Jawa Tengah
                </div>
            </div>

        </div>
    </div>
</div>

<form method="POST" action="{{ route('penilaian.store', 'taman') }}">
@csrf
<input type="hidden" name="next_section" value="keamanan">
<input type="hidden" name="current_section" value="taman">

{{-- ================= FOTO PEGAWAI ================= --}}
@if ($pegawais->count() > 0)
<div class="mb-5">
    <h5 class="fw-bold mb-3 text-center">
        <span class="badge bg-primary">Bidang Taman</span>
    </h5>

    <div class="row g-4 justify-content-center">
        @foreach ($pegawais as $pegawai)
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card border-0 shadow-sm h-100 text-center pegawai-card">
                <div class="card-body p-3 d-flex flex-column align-items-center">

                    <div class="foto-frame mb-3">
                        <img
                            src="{{ $pegawai->foto
                                ? asset('storage/' . $pegawai->foto)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=6c757d&color=ffffff&size=256' }}"
                            alt="{{ $pegawai->nama }}">
                    </div>

                    <div class="fw-semibold small">
                        {{ $pegawai->nama }}
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================= KRITERIA ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Kriteria Penilaian</h5>

        <ol>
            <li>Kualitas Kerja</li>
            <li>Kuantitas Kerja</li>
            <li>Kedisiplinan</li>
            <li>Kerja Sama</li>
            <li>Inisiatif</li>
            <li>Komunikasi</li>
            <li>Pengembangan Diri</li>
            <li>Loyalitas</li>
        </ol>

        <div class="alert alert-light border mt-3">
            <strong>Skala Penilaian:</strong>
            <div class="d-flex gap-3 mt-2 flex-wrap">
                <span>5 = Sangat Baik</span>
                <span>4 = Baik</span>
                <span>3 = Cukup</span>
                <span>2 = Kurang</span>
                <span>1 = Sangat Kurang</span>
            </div>
        </div>
    </div>
</div>

{{-- ================= TABEL PENILAIAN ================= --}}
<div class="card border-0 shadow-sm">
<div class="card-body">

<h5 class="fw-bold mb-3">Form Penilaian Pegawai</h5>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-light text-center">
<tr>
    <th width="25%">Nama Pegawai</th>
    <th width="25%">Nilai</th>
    <th width="50%">Catatan / Temuan</th>
</tr>
</thead>
<tbody>
@foreach ($pegawais as $pegawai)
<tr>
    <td class="fw-semibold">{{ $pegawai->nama }}</td>

    <td>
        <div class="d-flex justify-content-center gap-3">
            @for ($i = 1; $i <= 5; $i++)
            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="skor[{{ $pegawai->id }}]"
                       value="{{ $i }}"
                       required
                       {{ (isset($data['skor'][$pegawai->id]) && $data['skor'][$pegawai->id] == $i) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $i }}</label>
            </div>
            @endfor
        </div>
    </td>

    <td>
        <textarea class="form-control form-control-sm"
                  name="catatan[{{ $pegawai->id }}]"
                  rows="2"
                  placeholder="Masukkan kritik / saran...">{{ $data['catatan'][$pegawai->id] ?? '' }}</textarea>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

{{-- ================= NAVIGASI ================= --}}
<div class="d-flex justify-content-between mt-4">
    <button type="submit" name="action" value="prev"
            class="btn btn-secondary">
        Sebelumnya
    </button>

    <button type="submit" name="action" value="next"
            class="btn btn-primary">
        Selanjutnya
    </button>
</div>

</div>
</div>
</form>
</div>

{{-- ================= VALIDASI ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.querySelector('button[name="action"][value="next"]');

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            let valid = true;
            const radioNames = new Set();

            document.querySelectorAll('input[name^="skor["]').forEach(r => {
                radioNames.add(r.name);
            });

            for (const name of radioNames) {
                if (!document.querySelector(`input[name="${name}"]:checked`)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                e.preventDefault();
                alert('Harap isi semua nilai untuk setiap pegawai sebelum melanjutkan.');
            }
        });
    }
});
</script>

{{-- ================= STYLE ================= --}}
<style>
.pegawai-card { min-height: 240px; }

.foto-frame {
    width: 120px;
    height: 150px;
    background: #f1f3f5;
    border-radius: 8px;
    overflow: hidden;
}

.foto-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.form-check-input,
.form-check-label { cursor: pointer; }

textarea { resize: vertical; }
</style>
@endsection
