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
        <h6 class="fw-bold mb-1 text-uppercase">Kementerian Pendidikan Dasar dan Menengah</h6>
        <h6 class="fw-bold mb-1 text-uppercase">Republik Indonesia</h6>
        <h4 class="mt-3 fw-bold text-primary">Penilaian Alih Daya Tahun 2025</h4>
        <p class="mb-0 fw-semibold">Badan Pengembangan dan Pembinaan Bahasa</p>
        <p class="text-muted">Balai Bahasa Provinsi Jawa Tengah</p>
    </div>

    <form method="POST" action="{{ route('penilaian.store') }}">
        @csrf

        {{-- FOTO PEGAWAI --}}
        @if($sopir->count() > 0)
        <div class="mb-5">
            <h5 class="fw-bold mb-3 text-center">
                <span class="badge bg-primary">Sopir</span>
            </h5>

            <div class="row g-4 justify-content-center">
                @foreach($sopir as $pegawai)
                <div class="col-6 col-md-3 col-lg-2 text-center">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <img src="{{ $pegawai->foto
                                ? asset('storage/' . $pegawai->foto)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($pegawai->nama) . '&background=3699FF&color=ffffff&size=128' }}"
                                 class="rounded shadow-sm mb-2"
                                 style="width:120px;height:120px;object-fit:cover;"
                                 alt="{{ $pegawai->nama }}">

                            <div class="fw-semibold small">{{ $pegawai->nama }}</div>
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

                        {{-- SOPIR --}}
                        @foreach($sopir as $pegawai)
                        <tr>
                            <td class="fw-semibold">{{ $pegawai->nama }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-3">
                                    @for($i=1; $i<=5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="nilai[{{ $pegawai->id }}]"
                                               id="sopir_{{ $pegawai->id }}_{{ $i }}"
                                               value="{{ $i }}">
                                        <label class="form-check-label"
                                               for="sopir_{{ $pegawai->id }}_{{ $i }}">
                                            {{ $i }}
                                        </label>
                                    </div>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm"
                                          name="catatan[{{ $pegawai->id }}]"
                                          rows="2"
                                          placeholder="Masukkan catatan / temuan..."></textarea>
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Tombol Navigasi --> <div class="d-flex justify-content-between mt-4"> <button type="button" class="btn btn-secondary" onclick="history.back()"> Sebelumnya </button> <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.penilaian.index4') }}'"> Selanjutnya </button> </div>
            </div>
        </div>
    </form>
</div>

<style>
.form-check-input { cursor:pointer; }
.form-check-label { cursor:pointer; }
textarea { resize:vertical; }
</style>
@endsection
