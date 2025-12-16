@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-primary mb-1">Sistem Penilaian Kinerja Alih Daya</h4>
                    <p class="mb-0">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</p>
                </div>
                <img src="{{ asset('admin/img/illustrations/man-with-laptop-light.png') }}" height="110">
            </div>
        </div>
    </div>
</div>

{{-- RINGKASAN --}}
<div class="row g-4 mb-4">

    {{-- Total Pegawai ASN --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar rounded bg-primary me-3">
                    <i class="bx bx-user text-white fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Total Pegawai ASN</h6>
                    <h4 class="fw-bold mb-0">{{ $totalPegawai }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Alih Daya --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar rounded bg-info me-3">
                    <i class="bx bx-group text-white fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Total Alih Daya</h6>
                    <h4 class="fw-bold mb-0">{{ $totalAlihDaya }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- ASN Sudah Menilai --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar rounded bg-success me-3">
                    <i class="bx bx-check-circle text-white fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">ASN Sudah Menilai</h6>
                    <h4 class="fw-bold mb-0">{{ $sudahMenilai }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- ASN Belum Menilai --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="avatar rounded bg-warning me-3">
                    <i class="bx bx-time text-white fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">ASN Belum Menilai</h6>
                    <h4 class="fw-bold mb-0">{{ $belumMenilai }}</h4>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- PROGRESS --}}
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Progress Penilaian ASN</h6>

                <div class="d-flex justify-content-between mb-1">
                    <span>Sudah Menilai</span>
                    <span>{{ $persen }}%</span>
                </div>

                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                </div>

                <small class="text-muted d-block mt-2">
                    {{ $sudahMenilai }} dari {{ $totalPegawai }} pegawai ASN
                </small>
            </div>
        </div>
    </div>
</div>

@endsection
