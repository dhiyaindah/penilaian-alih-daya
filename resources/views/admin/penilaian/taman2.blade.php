@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold">Penilaian Kinerja Pegawai Alih Daya</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="mb-0 fw-bold">KEMENTERIAN</h5>
                <h5 class="mb-0 fw-bold">PENDIDIKAN DASAR DAN MENENGAH</h5>
                <h5 class="mb-0 fw-bold">REPUBLIK INDONESIA</h5>
                <h4 class="mt-3 fw-bold">Penilaian Alih Daya 2025</h4>
                <h5>Badan Pengembangan dan Pembinaan Bahasa</h5>
                <h5>Balai Bahasa Provinsi Jawa Tengah</h5>
            </div>

            <form method="POST" action="{{ route('penilaian.store', 'taman') }}">
                @csrf
                <input type="hidden" name="next_section" value="keamanan">
                <input type="hidden" name="current_section" value="taman">
                <!-- <input type="hidden" name="penilai_id" value="{{ session('penilai.id') }}"> -->

                <!-- Bagian untuk Foto Pegawai -->
                <div class="row mb-5">
                    <!-- Bidang taman -->
                    @if($pegawais->count() > 0)
                    <div class="col-md-12 mb-4">
                        <h5 class="fw-bold mb-3 text-center">Bidang taman</h5>
                        <div class="row text-center">
                            @foreach($pegawais as $pegawai)
                            <div class="col-md-3 mb-3">
                                <!-- Foto Pegawai -->
                                <div class="mb-2">
                                    <div style="
                                        width: 120px;
                                        height: 120px;
                                        background-color: #e0e0e0;
                                        border-radius: 0;
                                        margin: 0 auto;
                                        border: 1px solid #ccc;
                                        overflow: hidden;
                                    ">
                                        <img src="{{ $pegawai->foto 
                                            ? asset('storage/' . $pegawai->foto) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=3699FF&color=ffffff&size=128' }}"
                                            class="border border-3 border-white shadow-sm"
                                            style="width: 120px; height: 120px; object-fit: cover; border-radius: 0;"
                                            alt="{{ $pegawai->nama }}">
                                        <!-- Placeholder untuk foto -->
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <span class="text-muted">Foto</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nama Pegawai -->
                                <p class="fw-bold mb-1">{{ $pegawai->nama }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Bagian Penilaian -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    Kriteria Penilaian
                                </h5>

                                <ol class="mb-4">
                                    <li><strong>Kualitas Kerja</strong> – Kemampuan menyelesaikan tugas dengan baik dan akurat.</li>
                                    <li><strong>Kuantitas Kerja</strong> – Jumlah pekerjaan yang diselesaikan dalam waktu yang ditentukan.</li>
                                    <li><strong>Kedisiplinan</strong> – Kepatuhan dalam menjalankan tugas dan mengikuti aturan.</li>
                                    <li><strong>Kerja Sama</strong> – Kemampuan bekerja sama dengan rekan kerja dan tim.</li>
                                    <li><strong>Inisiatif</strong> – Kemampuan mengambil inisiatif serta memecahkan masalah.</li>
                                    <li><strong>Komunikasi</strong> – Kemampuan berkomunikasi secara efektif dengan rekan kerja dan atasan.</li>
                                    <li><strong>Pengembangan Diri</strong> – Upaya meningkatkan kemampuan dan pengetahuan diri.</li>
                                    <li><strong>Loyalitas</strong> – Kesetiaan dan komitmen terhadap organisasi.</li>
                                </ol>

                                <p class="mb-2 fw-semibold">
                                    Silakan memilih kategori penilaian:
                                </p>

                                <ul class="list-unstyled">
                                    <li>Sangat Baik <small class="text-muted">(Skor 5)</small></li>
                                    <li>Baik <small class="text-muted">(Skor 4)</small></li>
                                    <li>Cukup <small class="text-muted">(Skor 3)</small></li>
                                    <li>Kurang <small class="text-muted">(Skor 2)</small></li>
                                    <li>Sangat Kurang <small class="text-muted">(Skor 1)</small></li>
                                </ul>
                                <!-- Tabel Penilaian -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th width="30%">Nama Pegawai</th>
                                                <th width="20%">Penilaian</th>
                                                <th width="50%">Catatan/Temuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Bidang taman -->
                                            @foreach($pegawais as $pegawai)
                                            <tr>
                                                <td class="fw-bold">{{ $pegawai->nama }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        @for($i = 5; $i >= 1; $i--)
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
                                                    <textarea class="form-control form-control-sm"
                                                        name="catatan[{{ $pegawai->id }}]"
                                                        rows="2">{{ $data['catatan'][$pegawai->id] ?? '' }}</textarea>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tombol Navigasi -->
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
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-check-input {
        cursor: pointer;
    }
    
    .form-check-label {
        cursor: pointer;
        font-weight: normal;
    }
    
    table {
        font-size: 14px;
    }
    
    table th {
        background-color: #f8f9fa;
        text-align: center;
        vertical-align: middle;
    }
    
    table td {
        vertical-align: middle;
    }
    
    .form-check {
        margin-bottom: 0;
    }
    
    textarea {
        resize: vertical;
        min-height: 60px;
    }
</style>
@endsection