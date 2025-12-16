@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold">Rekap Penilaian</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        {{-- HEADER CARD --}}
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="bi bi-file-text me-2"></i>Rekap Penilaian Pegawai Alih Daya
                    </h5>
                    <p class="mb-0 text-muted">
                        <small>Data rekap penilaian seluruh pegawai berdasarkan bidang</small>
                    </p>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-people me-1"></i> {{ $rekap->count() }} Pegawai
                    </span>
                    <!-- <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button> -->
                    <a href="{{ route('penilaian.export.perpegawai') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-excel me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('penilaian.detail') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-file-excel me-1"></i> Detail Penilaian
                    </a>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="card-body border-bottom bg-light">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <label class="form-label small text-muted">Bidang</label>
                    <select class="form-select form-select-sm" onchange="filterBidang(this)">
                        <option value="">Semua Bidang</option>
                        <option value="kebersihan">Kebersihan</option>
                        <option value="taman">Taman</option>
                        <option value="keamanan">Keamanan</option>
                        <option value="sopir">Sopir</option>
                    </select>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <label class="form-label small text-muted">Urutkan</label>
                    <select class="form-select form-select-sm" onchange="sortData(this)">
                        <option value="nama">Nama A-Z</option>
                        <option value="nama_desc">Nama Z-A</option>
                        <option value="nilai_desc">Nilai Tertinggi</option>
                        <option value="nilai_asc">Nilai Terendah</option>
                        <option value="jabatan">Bidang</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="ps-4">#</th>
                            <th width="35%">Nama Pegawai</th>
                            <th width="25%">Bidang</th>
                            <th width="20%" class="text-center">Rata-rata Nilai</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rekap as $index => $row)
                            <tr class="hover-row" data-bidang="{{ $row->jabatan }}">
                                {{-- NO --}}
                                <td class="ps-4">
                                    <span class="text-muted">{{ $index + 1 }}</span>
                                </td>

                                {{-- NAMA --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px symbol-circle me-3">
                                            <img
                                        src="{{ $row->foto 
                                                ? asset('storage/' . $row->foto) 
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($row->nama) . '&background=cccccc&color=ffffff' }}"
                                        class="w-px-40 h-px-40 rounded-circle object-cover"
                                    />
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $row->nama }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- JABATAN --}}
                                <td>
                                    <span class="badge badge-bidang bg-{{ $row->jabatan == 'kebersihan' ? 'info' : ($row->jabatan == 'taman' ? 'success' : ($row->jabatan == 'keamanan' ? 'warning' : 'secondary')) }}">
                                        <i class="bi bi-{{ $row->jabatan == 'kebersihan' ? 'droplet' : ($row->jabatan == 'taman' ? 'tree' : ($row->jabatan == 'keamanan' ? 'shield-check' : 'truck')) }} me-1"></i>
                                        {{ ucfirst($row->jabatan) }}
                                    </span>
                                </td>

                                {{-- RATA-RATA --}}
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="position-relative" style="width: 100px;">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $row->rata_rata >= 4 ? 'success' : ($row->rata_rata >= 3 ? 'warning' : 'danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ ($row->rata_rata / 5) * 100 }}%"
                                                     aria-valuenow="{{ $row->rata_rata }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="5">
                                                </div>
                                            </div>
                                            <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center justify-content-center">
                                                <span class="fw-bold text-dark" style="font-size: 0.75rem;">
                                                    {{ number_format($row->rata_rata, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            @php
                                                $icon = match(true) {
                                                    $row->rata_rata >= 4.5 => 'bi-star-fill text-warning',
                                                    $row->rata_rata >= 4 => 'bi-star-half text-warning',
                                                    $row->rata_rata >= 3 => 'bi-star text-warning',
                                                    default => 'bi-star text-muted'
                                                };
                                            @endphp
                                            <i class="bi {{ $icon }}"></i>
                                        </div>
                                    </div>
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <a href="{{ route('penilaian.detail', ['alih_daya_id' => $row->id]) }}" 
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-clipboard-x display-6 text-muted"></i>
                                        <h6 class="mt-3 text-muted">Tidak ada data penilaian</h6>
                                        <p class="text-muted small">Belum ada penilaian yang tercatat</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER CARD --}}
        @if($rekap->count() > 0)
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted jumlah-data">
                        Menampilkan {{ $rekap->count() }} dari {{ $rekap->total() ?? $rekap->count() }} data
                    </small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Rata-rata keseluruhan:</span>
                        <span class="badge bg-primary">
                            {{ number_format($rekap->avg('rata_rata') ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailContent">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>
</div>

<style>
    .hover-row:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .symbol-40px {
        width: 40px;
        height: 40px;
    }
    
    .symbol-circle {
        border-radius: 50%;
    }
    
    .symbol-label {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        width: 100%;
        height: 100%;
    }
    
    .badge-bidang {
        padding: 0.4em 0.8em;
        font-size: 0.75rem;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
    }
    
    .progress-bar {
        border-radius: 4px;
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
</style>

<script>
// function filterBidang(select) {
//     const value = select.value;
//     const rows = document.querySelectorAll('tbody tr[data-bidang]');
    
//     rows.forEach(row => {
//         if (!value || row.dataset.bidang === value) {
//             row.style.display = '';
//         } else {
//             row.style.display = 'none';
//         }
//     });
// }

function filterBidang(select) {
    const value = select.value.toLowerCase(); // Pastikan lowercase untuk konsistensi
    const rows = document.querySelectorAll('tbody tr[data-bidang]');
    
    rows.forEach(row => {
        const bidang = row.dataset.bidang.toLowerCase();
        
        if (!value || bidang === value) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update jumlah data yang ditampilkan
    updateJumlahData();
}

function updateJumlahData() {
    const visibleRows = document.querySelectorAll('tbody tr[data-bidang]:not([style*="display: none"])').length;
    const totalRows = document.querySelectorAll('tbody tr[data-bidang]').length;
    
    // Update teks jumlah data
    const jumlahText = document.querySelector('.jumlah-data');
    if (jumlahText) {
        jumlahText.textContent = `Menampilkan ${visibleRows} dari ${totalRows} data`;
    }
}

// Panggil updateJumlahData saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateJumlahData();
});

function sortData(select) {
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr[data-bidang]'));
    
    const sortBy = select.value;
    
    rows.sort((a, b) => {
        const namaA = a.querySelector('.fw-semibold').textContent.toLowerCase();
        const namaB = b.querySelector('.fw-semibold').textContent.toLowerCase();
        const jabatanA = a.dataset.bidang;
        const jabatanB = b.dataset.bidang;
        const nilaiA = parseFloat(a.querySelector('.progress-bar').dataset.value || a.querySelector('.progress-bar').ariaValueNow);
        const nilaiB = parseFloat(b.querySelector('.progress-bar').dataset.value || b.querySelector('.progress-bar').ariaValueNow);
        
        switch(sortBy) {
            case 'nama':
                return namaA.localeCompare(namaB);
            case 'nama_desc':
                return namaB.localeCompare(namaA);
            case 'jabatan':
                return jabatanA.localeCompare(jabatanB);
            case 'nilai_desc':
                return nilaiB - nilaiA;
            case 'nilai_asc':
                return nilaiA - nilaiB;
            default:
                return 0;
        }
    });
    
    // Update nomor urut
    rows.forEach((row, index) => {
        tbody.appendChild(row);
        row.querySelector('td:first-child span').textContent = index + 1;
    });
}

function showDetail(id) {
    // Implementasi AJAX untuk mengambil detail penilaian
    fetch(`/admin/penilaian/detail/${id}`)
        .then(response => response.json())
        .then(data => {
            const modalContent = document.getElementById('modalDetailContent');
            modalContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Nama</label>
                            <p class="fw-semibold">${data.nama}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Bidang</label>
                            <p>${data.jabatan}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Total Penilaian</label>
                            <p class="fw-semibold">${data.total_penilaian || 0} kali</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Rata-rata</label>
                            <p class="fw-bold text-primary">${data.rata_rata || 0}</p>
                        </div>
                    </div>
                </div>
                ${data.detail ? `
                <div class="mt-3">
                    <h6 class="mb-3">Detail Penilaian</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Penilai</th>
                                    <th>Nilai</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.detail.map(item => `
                                    <tr>
                                        <td>${item.tanggal}</td>
                                        <td>${item.penilai}</td>
                                        <td><span class="badge bg-primary">${item.nilai}</span></td>
                                        <td>${item.catatan || '-'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                ` : ''}
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Inisialisasi tooltip jika menggunakan Bootstrap tooltip
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection