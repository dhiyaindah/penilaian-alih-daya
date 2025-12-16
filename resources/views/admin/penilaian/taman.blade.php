@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold">
        Penilaian Kinerja Pegawai Alih Daya
    </li>
@endsection

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h6 class="fw-bold mb-1 text-uppercase">
            Kementerian Pendidikan Dasar dan Menengah
        </h6>
        <h6 class="fw-bold mb-1 text-uppercase">
            Republik Indonesia
        </h6>
        <h4 class="mt-3 fw-bold text-primary">
            Penilaian Alih Daya Tahun 2025
        </h4>
        <p class="mb-0 fw-semibold">
            Badan Pengembangan dan Pembinaan Bahasa
        </p>
        <p class="text-muted">
            Balai Bahasa Provinsi Jawa Tengah
        </p>
    </div>

    <form method="POST" action="{{ route('penilaian.store', 'taman') }}">
        @csrf
        <input type="hidden" name="next_section" value="keamanan">
        <input type="hidden" name="current_section" value="taman">

        {{-- FOTO PEGAWAI --}}
        @if ($pegawais->count() > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-3 text-center">
                    <span class="badge bg-primary">Bidang Taman</span>
                </h5>

                <div class="row g-4 justify-content-center">
                    @foreach ($pegawais as $pegawai)
                        <div class="col-6 col-md-3 col-lg-2 text-center">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <img
                                        src="{{ $pegawai->foto
                                            ? asset('storage/' . $pegawai->foto)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=3699FF&color=ffffff&size=128' }}"
                                        class="rounded shadow-sm mb-2"
                                        style="width:120px;height:120px;object-fit:cover;"
                                        alt="{{ $pegawai->nama }}"
                                    >

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

        {{-- KRITERIA --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Kriteria Penilaian</h5>

                <ol class="mb-4">
                    <li><strong>Kualitas Kerja</strong></li>
                    <li><strong>Kuantitas Kerja</strong></li>
                    <li><strong>Kedisiplinan</strong></li>
                    <li><strong>Kerja Sama</strong></li>
                    <li><strong>Inisiatif</strong></li>
                    <li><strong>Komunikasi</strong></li>
                    <li><strong>Pengembangan Diri</strong></li>
                    <li><strong>Loyalitas</strong></li>
                </ol>

                <div class="alert alert-light border">
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

        {{-- TABEL PENILAIAN --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    Form Penilaian Pegawai
                </h5>

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
                                    <td class="fw-semibold">
                                        {{ $pegawai->nama }}
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="skor[{{ $pegawai->id }}]"
                                                        value="{{ $i }}"
                                                        {{ (isset($data['skor'][$pegawai->id]) && $data['skor'][$pegawai->id] == $i) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="taman_{{ $pegawai->id }}_{{ $i }}">
                                                        {{ $i }}
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </td>

                                    <td>
                                        <textarea
                                            class="form-control form-control-sm"
                                            name="catatan[{{ $pegawai->id }}]"
                                            rows="2"
                                            placeholder="Masukkan kritik / saran..."
                                        >{{ $data['catatan'][$pegawai->id] ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TOMBOL NAVIGASI --}}
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

<script>
    document.getElementById('penilai_id')?.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const nip = selected.getAttribute('data-nip') || '';
        document.getElementById('nip_penilai').value = nip;
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.querySelector('button[name="action"][value="next"]');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            let isValid = true;
            
            // Cek setiap grup radio
            const radioNames = new Set();
            document.querySelectorAll('input[name^="skor["]').forEach(radio => {
                radioNames.add(radio.name);
            });
            
            // Validasi setiap grup
            for (const name of radioNames) {
                const radios = document.querySelectorAll(`input[name="${name}"]:checked`);
                if (radios.length === 0) {
                    isValid = false;
                    break;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap isi semua nilai untuk setiap pegawai sebelum melanjutkan.');
                return false;
            }
        });
    }
});
</script>

<style>
    .form-check-input { cursor: pointer; }
    .form-check-label { cursor: pointer; }
    textarea { resize: vertical; }
</style>
@endsection
