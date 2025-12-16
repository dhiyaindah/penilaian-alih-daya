@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaian.rekap') }}">Rekap Penilaian</a></li>
    <li class="breadcrumb-item active text-primary fw-semibold">Detail Penilaian</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        {{-- HEADER CARD --}}
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="bi bi-list-check me-2"></i>Detail Semua Penilaian
                    </h5>
                    <p class="mb-0 text-muted">
                        <small>Total {{ $penilaian->total() }} data penilaian</small>
                    </p>
                </div>
                
                <div class="d-flex gap-2">
                    <!-- <a href="{{ route('penilaian.export.excel') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-excel me-1"></i> Export
                    </a> -->
                    <!-- <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button> -->
                </div>
            </div>
        </div>

        {{-- FILTER DAN GROUPING --}}
        <div class="card-body border-bottom bg-light">
            <form method="GET" action="{{ route('penilaian.detail') }}" class="row g-3">
                <div class="col-md-4 col-lg-2">
                    <label class="form-label small text-muted">Bidang</label>
                    <select name="bidang" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Bidang</option>
                        <option value="kebersihan" {{ request('bidang') == 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                        <option value="taman" {{ request('bidang') == 'taman' ? 'selected' : '' }}>Taman</option>
                        <option value="keamanan" {{ request('bidang') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                        <option value="sopir" {{ request('bidang') == 'sopir' ? 'selected' : '' }}>Sopir</option>
                    </select>
                </div>
                
                <div class="col-md-4 col-lg-3">
                    <label class="form-label small text-muted">Pegawai Alih Daya</label>
                    <select name="alih_daya_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawaiList as $peg)
                            <option value="{{ $peg->id }}" {{ request('alih_daya_id') == $peg->id ? 'selected' : '' }}>
                                {{ $peg->nama }} - {{ $peg->jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 col-lg-2">
                    <label class="form-label small text-muted">Group By</label>
                    <select name="group_by" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Tidak Digroup</option>
                        <option value="alih_daya" {{ request('group_by') == 'alih_daya' ? 'selected' : '' }}>Pegawai Alih Daya</option>
                        <option value="bidang" {{ request('group_by') == 'bidang' ? 'selected' : '' }}>Bidang</option>
                        <option value="penilai" {{ request('group_by') == 'penilai' ? 'selected' : '' }}>Penilai</option>
                    </select>
                </div>
                
                <div class="col-md-4 col-lg-1 d-flex align-items-end">
                    <a href="{{ route('penilaian.detail') }}" class="btn btn-outline-secondary btn-sm w-100"> Reset
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- TABLE DETAIL --}}
        <div class="card-body p-0">
            @if(request('group_by') == 'alih_daya')
                {{-- GROUP BY PEGAWAI ALIH DAYA --}}
                @foreach($groupedData as $pegawaiId => $penilaianGroup)
                    @php $pegawai = $penilaianGroup->first(); @endphp
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 px-3 pt-3">
                            <div class="d-flex align-items-center">
                                @if($pegawai->foto)
                                    <img src="{{ asset('storage/' . $pegawai->foto) }}" 
                                         class="rounded-circle me-3"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="symbol symbol-40px symbol-circle me-3">
                                        <div class="symbol-label bg-primary bg-opacity-10 text-primary">
                                            {{ substr($pegawai->nama_pegawai, 0, 1) }}
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $pegawai->nama_pegawai }}</h6>
                                    <small class="text-muted">
                                        <span class="badge bg-{{ $pegawai->jabatan == 'kebersihan' ? 'info' : ($pegawai->jabatan == 'taman' ? 'success' : ($pegawai->jabatan == 'keamanan' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst($pegawai->jabatan) }}
                                        </span>
                                        • {{ $penilaianGroup->count() }} penilaian
                                        • Rata-rata: <strong>{{ number_format($penilaianGroup->avg('skor'), 2) }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th width="5%">#</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="20%">Penilai</th>
                                        <th width="10%" class="text-center">Skor</th>
                                        <th width="15%" class="text-center">Keterangan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaianGroup as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                            <td>{{ $row->nama_penilai }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $row->skor >= 4 ? 'success' : ($row->skor >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $row->skor }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @switch($row->skor)
                                                    @case(5)<span class="text-success">Sangat Baik</span>@break
                                                    @case(4)<span class="text-primary">Baik</span>@break
                                                    @case(3)<span class="text-warning">Cukup</span>@break
                                                    @case(2)<span class="text-danger">Kurang</span>@break
                                                    @case(1)<span class="text-danger">Sangat Kurang</span>@break
                                                @endswitch
                                            </td>
                                            <td>{{ $row->catatan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                
            @elseif(request('group_by') == 'bidang')
                {{-- GROUP BY BIDANG --}}
                @foreach($groupedData as $bidang => $penilaianGroup)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 px-3 pt-3">
                            <div>
                                <h6 class="mb-0 fw-bold text-uppercase">{{ ucfirst($bidang) }}</h6>
                                <small class="text-muted">
                                    {{ $penilaianGroup->count() }} penilaian • 
                                    Rata-rata: <strong>{{ number_format($penilaianGroup->avg('skor'), 2) }}</strong>
                                </small>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th width="5%">#</th>
                                        <th width="25%">Pegawai Alih Daya</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="20%">Penilai</th>
                                        <th width="10%" class="text-center">Skor</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaianGroup as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $row->nama_pegawai }}</td>
                                            <td>{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                            <td>{{ $row->nama_penilai }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $row->skor >= 4 ? 'success' : ($row->skor >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $row->skor }}
                                                </span>
                                            </td>
                                            <td>{{ $row->catatan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                
            @elseif(request('group_by') == 'penilai')
                {{-- GROUP BY PENILAI --}}
                @foreach($groupedData as $penilaiId => $penilaianGroup)
                    @php $penilai = $penilaianGroup->first(); @endphp
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 px-3 pt-3">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $penilai->nama_penilai }}</h6>
                                <small class="text-muted">
                                    {{ $penilaianGroup->count() }} penilaian • 
                                    Rata-rata: <strong>{{ number_format($penilaianGroup->avg('skor'), 2) }}</strong>
                                </small>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th width="5%">#</th>
                                        <th width="25%">Pegawai Alih Daya</th>
                                        <th width="15%">Bidang</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="10%" class="text-center">Skor</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaianGroup as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $row->nama_pegawai }}</td>
                                            <td>
                                                <span class="badge bg-{{ $row->jabatan == 'kebersihan' ? 'info' : ($row->jabatan == 'taman' ? 'success' : ($row->jabatan == 'keamanan' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($row->jabatan) }}
                                                </span>
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $row->skor >= 4 ? 'success' : ($row->skor >= 3 ? 'warning' : 'danger') }}">
                                                    {{ $row->skor }}
                                                </span>
                                            </td>
                                            <td>{{ $row->catatan ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                
            @else
                {{-- TANPA GROUPING (DEFAULT) --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="ps-3">#</th>
                                <th width="25%">Pegawai Alih Daya</th>
                                <th width="15%">Bidang</th>
                                <th width="15%">Penilai</th>
                                <th width="10%" class="text-center">Skor</th>
                                <th width="15%" class="text-center">Keterangan</th>
                                <th>Catatan</th>
                                <th width="12%" class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penilaian as $index => $row)
                                <tr>
                                    <td class="ps-3">{{ $penilaian->firstItem() + $index }}</td>
                                    
                                    {{-- NAMA PEGAWAI ALIH DAYA --}}
                                    <td class="fw-semibold">
                                        <div class="d-flex align-items-center">
                                            @if($row->foto)
                                                <img src="{{ asset('storage/' . $row->foto) }}" 
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="symbol symbol-32px symbol-circle me-2">
                                                    <div class="symbol-label bg-primary bg-opacity-10 text-primary">
                                                        {{ substr($row->nama_pegawai, 0, 1) }}
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <div>{{ $row->nama_pegawai }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- BIDANG --}}
                                    <td>
                                        <span class="badge bg-{{ $row->jabatan == 'kebersihan' ? 'info' : ($row->jabatan == 'taman' ? 'success' : ($row->jabatan == 'keamanan' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst($row->jabatan) }}
                                        </span>
                                    </td>
                                    
                                    {{-- PENILAI --}}
                                    <td class="small">{{ $row->nama_penilai }}</td>
                                    
                                    {{-- SKOR --}}
                                    <td class="text-center">
                                        <span class="badge bg-{{ $row->skor >= 4 ? 'success' : ($row->skor >= 3 ? 'warning' : 'danger') }}">
                                            {{ $row->skor }}
                                        </span>
                                    </td>
                                    
                                    {{-- KETERANGAN SKOR --}}
                                    <td class="text-center">
                                        @switch($row->skor)
                                            @case(5) <span class="text-success">Sangat Baik</span> @break
                                            @case(4) <span class="text-primary">Baik</span> @break
                                            @case(3) <span class="text-warning">Cukup</span> @break
                                            @case(2) <span class="text-danger">Kurang</span> @break
                                            @case(1) <span class="text-danger">Sangat Kurang</span> @break
                                        @endswitch
                                    </td>
                                    
                                    {{-- CATATAN --}}
                                    <td>{{ $row->catatan ?? '-' }}</td>
                                    
                                    {{-- TANGGAL --}}
                                    <td class="text-center small">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- PAGINATION --}}
        @if($penilaian->hasPages() && !request('group_by'))
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $penilaian->firstItem() }} - {{ $penilaian->lastItem() }} dari {{ $penilaian->total() }} data
                </small>
                
                {{ $penilaian->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .symbol-32px { width: 32px; height: 32px; }
    .symbol-40px { width: 40px; height: 40px; }
    
    .symbol-circle { border-radius: 50%; }
    
    .symbol-label {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        width: 100%;
        height: 100%;
    }
    
    .table th { font-weight: 600; }
    .table td { vertical-align: middle; }
    
    .pagination { margin-bottom: 0; }
    
    @media print {
        .card-header, .card-footer, .bg-light { display: none !important; }
        .table { font-size: 11px; }
    }
</style>
@endsection