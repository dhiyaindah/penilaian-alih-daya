@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold">Penilaian Kinerja Pegawai Alih Daya</li>
@endsection

@section('content')
<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1 fw-semibold text-dark">Penilaian Kinerja Pegawai Alih Daya</h5>
            <small class="text-muted">Total {{ $alih_dayas->count() }} pegawai alih daya yang perlu dinilai</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('penilaian.rekap') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bx bx-list-ul me-2"></i>Rekap Penilaian
            </a>
        </div>
    </div>

    <!-- Filter/Search Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bx bx-search"></i>
                </span>
                <input type="text" class="form-control border-start-0" 
                       placeholder="Cari pegawai berdasarkan nama atau NIP...">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select">
                <option value="">Semua Status</option>
                <option value="belum">Belum Dinilai</option>
                <option value="sudah">Sudah Dinilai</option>
                <option value="sedang">Sedang Dinilai</option>
            </select>
        </div>
    </div>

    <!-- Card Grid Layout -->
    <div class="row g-4">
        @forelse($alih_dayas as $alih_daya)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 border border-2 border-hover-primary">
                <div class="card-body p-4">
                    <!-- Profile Photo & Status -->
                    <div class="text-center">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="symbol symbol-100px symbol-circle">
                                <img src="{{ $alih_daya->foto 
                                    ? asset('storage/' . $alih_daya->foto) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($alih_daya->nama) . '&background=3699FF&color=ffffff&size=128' }}"
                                     class="rounded-circle border border-3 border-white shadow-sm"
                                     style="width: 100px; height: 100px; object-fit: cover;"
                                     alt="{{ $alih_daya->nama }}">
                            </div>
                            <!-- Status Badge -->
                            <div class="position-absolute bottom-0 end-0">
                                @if($alih_daya->status_penilaian == 'sudah')
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bx-check-circle me-1"></i>Sudah
                                </span>
                                @elseif($alih_daya->status_penilaian == 'sedang')
                                <span class="badge bg-warning rounded-pill px-3 py-2">
                                    <i class="bx bx-time me-1"></i>Sedang
                                </span>
                                @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                    <i class="bx bx-time-five me-1"></i>Belum
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Employee Name -->
                        <h5 class="fw-bold text-dark mb-1">{{ $alih_daya->nama }}</h5>
                        
                        <!-- Position -->
                        <div class="badge bg-light-primary text-primary fw-semibold px-3 py-2 mb-3">
                            <i class="bx bx-briefcase me-1"></i>
                            {{ $alih_daya->jabatan ?: 'Pegawai Alih Daya' }}
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="border-top pt-3">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-muted small">Periode</div>
                                <div class="fw-semibold text-dark">{{ $alih_daya->periode_penilaian ?: 'Q4 2024' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Total Nilai</div>
                                <div class="fw-bold fs-5 text-primary">
                                    @if($alih_daya->rata_rata_nilai)
                                        {{ number_format($alih_daya->rata_rata_nilai, 1) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mt-3">
                        @if($alih_daya->status_penilaian == 'belum')
                        <a href="{{ route('penilaian.create', $alih_daya->id) }}" 
                           class="btn btn-primary d-flex align-items-center justify-content-center">
                            <i class="bx bx-clipboard me-2"></i>Beri Nilai
                        </a>
                        @elseif($alih_daya->status_penilaian == 'sedang')
                        <div class="d-flex gap-2">
                            <a href="{{ route('penilaian.edit', $alih_daya->id) }}" 
                               class="btn btn-warning flex-fill d-flex align-items-center justify-content-center">
                                <i class="bx bx-edit me-2"></i>Lanjutkan
                            </a>
                            <button class="btn btn-light btn-icon" title="Lihat draft">
                                <i class="bx bx-file"></i>
                            </button>
                        </div>
                        @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('penilaian.show', $alih_daya->id) }}" 
                               class="btn btn-success flex-fill d-flex align-items-center justify-content-center">
                                <i class="bx bx-show me-2"></i>Lihat Nilai
                            </a>
                            <!-- <a href="{{ route('penilaian.edit', $alih_daya->id) }}" 
                               class="btn btn-light btn-icon" title="Edit nilai">
                                <i class="bx bx-edit"></i>
                            </a> -->
                        </div>
                        @endif
                        
                        <!-- Additional Action -->
                        <!-- <div class="d-flex gap-2">
                            <a href="{{ route('alih_daya.show', $alih_daya->id) }}" 
                               class="btn btn-outline-primary flex-fill">
                                <i class="bx bx-user me-1"></i>Profil
                            </a>
                            <button class="btn btn-outline-secondary btn-icon" 
                                    title="Riwayat penilaian"
                                    onclick="showHistory({{ $alih_daya->id }})">
                                <i class="bx bx-history"></i>
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-12">
            <div class="card border-dashed border-2">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bx bx-user-x bx-lg text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Tidak ada pegawai alih daya</h5>
                    <p class="text-muted mb-4">
                        Belum ada data pegawai alih daya yang tersedia untuk dinilai.
                    </p>
                    <a href="{{ route('alih_daya.index') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-2"></i>Tambah Pegawai
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alih_dayas->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
        <div class="mb-3 mb-md-0 text-muted">
            <span class="fw-medium">Menampilkan</span>
            <span class="fw-medium">{{ $alih_dayas->firstItem() ?? 0 }}</span>
            <span class="fw-medium">sampai</span>
            <span class="fw-medium">{{ $alih_dayas->lastItem() ?? 0 }}</span>
            <span class="fw-medium">dari</span>
            <span class="fw-medium">{{ $alih_dayas->total() }}</span>
            <span class="fw-medium">data pegawai alih daya</span>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <!-- First Page Link -->
                @if(!$alih_dayas->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $alih_dayas->url(1) }}" aria-label="First">
                        <i class="bx bx-chevrons-left"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevrons-left"></i></span>
                </li>
                @endif

                <!-- Previous Page Link -->
                @if($alih_dayas->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $alih_dayas->previousPageUrl() }}" aria-label="Previous">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
                @endif

                <!-- Page Numbers -->
                @foreach($alih_dayas->getUrlRange(max(1, $alih_dayas->currentPage() - 2), min($alih_dayas->lastPage(), $alih_dayas->currentPage() + 2)) as $page => $url)
                <li class="page-item {{ $page == $alih_dayas->currentPage() ? 'active' : '' }}">
                    @if($page == $alih_dayas->currentPage())
                    <span class="page-link">{{ $page }}</span>
                    @else
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                </li>
                @endforeach

                <!-- Next Page Link -->
                @if($alih_dayas->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $alih_dayas->nextPageUrl() }}" aria-label="Next">
                        <i class="bx bx-chevron-right"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                </li>
                @endif

                <!-- Last Page Link -->
                @if($alih_dayas->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $alih_dayas->url($alih_dayas->lastPage()) }}" aria-label="Last">
                        <i class="bx bx-chevrons-right"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevrons-right"></i></span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
    @elseif($alih_dayas->total() > 0)
    <div class="mt-4 pt-3 border-top">
        <div class="text-center text-muted">
            Menampilkan semua {{ $alih_dayas->total() }} data pegawai alih daya
        </div>
    </div>
    @endif
</div>

<!-- Modal for History -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="historyContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for History Modal -->
<script>
function showHistory(pegawaiId) {
    // Load history data via AJAX
    fetch(`/penilaian/history/${pegawaiId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('historyContent').innerHTML = data;
            const modal = new bootstrap.Modal(document.getElementById('historyModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('historyContent').innerHTML = 
                '<div class="text-center py-4 text-muted">Gagal memuat riwayat penilaian</div>';
        });
}
</script>

<!-- Custom CSS -->
<style>
.card {
    transition: all 0.3s ease;
    border-radius: 12px;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.border-hover-primary:hover {
    border-color: #3699FF !important;
}
.symbol {
    display: inline-block;
    flex-shrink: 0;
    position: relative;
    border-radius: 50%;
}
.symbol-100px {
    width: 100px !important;
    height: 100px !important;
}
.symbol-circle {
    border-radius: 50%;
}
.btn-icon {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.border-dashed {
    border-style: dashed !important;
}
</style>
@endsection