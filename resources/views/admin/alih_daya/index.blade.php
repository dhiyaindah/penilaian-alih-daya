@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item active text-primary fw-semibold">Data Tim Alih Daya</li>
@endsection

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h5 class="mb-1 fw-semibold text-dark">Data Tim Alih Daya</h5>
            <small class="text-muted">Total {{ $alih_dayas->total() }} tim alih daya</small>
        </div>
        <div class="d-flex gap-2">
            <div class="card-toolbar">
                <a href="{{ route('alih_daya.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="bx bx-plus me-2"></i>Tambah Data
                </a>
            </div>
            
            <!-- Import Button -->
            <div class="dropdown">
                <button class="btn btn-outline-primary d-flex align-items-center" type="button" 
                        data-bs-toggle="modal" data-bs-target="#importModal" aria-expanded="false">
                    <i class="bx bx-import me-2"></i>Import Data
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($alih_dayas as $item)
                    <tr>
                        <!-- Nomor urut sesuai halaman -->
                        <td>{{ $alih_dayas->firstItem() + $loop->index }}</td>

                        <!-- Nama -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-36px symbol-circle me-3">
                                    <img
                                        src="{{ $item->foto 
                                                ? asset('storage/' . $item->foto) 
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($item->nama) . '&background=cccccc&color=ffffff' }}"
                                        class="w-px-40 h-px-40 rounded-circle object-cover"
                                    />
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $item->nama }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Jabatan -->
                        <td>{{ $item->jabatan }}</td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('alih_daya.edit', [$item->id, 'page' => request()->input('page', 1)]) }}" 
                                   class="text-primary me-3" title="Edit">
                                    <i class="bx bx-edit bx-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Tidak ada data tim alih daya.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

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
</div>
<!-- Modal Impor Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Impor Data Alih Daya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('alih_daya.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <label class="form-label fw-semibold">Pilih File Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls"
                        class="form-control @error('file') is-invalid @enderror">

                    <div class="form-text mt-1">
                        Hanya menerima file Excel (.xlsx/.xls). Maksimal 2 MB.
                    </div>

                    <!-- Link Download Template -->
                    <div class="mt-2">
                        <a href="{{ asset('template/template_import_alih_daya.xlsx') }}" 
                        class="text-primary fw-semibold" 
                        download>
                            📥 Download Template Excel
                        </a>
                    </div>

                    @error('file')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Impor</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        html: `
            <span style="font-size:14px; color:#555;">
                Data pegawai alih daya yang dihapus <strong>tidak dapat dipulihkan.</strong>
            </span>
        `,
        icon: 'warning',
        iconColor: '#f0ad4e',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#e55353',
        cancelButtonColor: '#6c757d',
        customClass: {
            popup: 'rounded-4 shadow-lg',
            confirmButton: 'px-4 py-2',
            cancelButton: 'px-4 py-2'
        },
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-' + id).submit();
        }
    });
}
</script>
@endsection


<style>
.pagination {
    margin-bottom: 0;
}
.page-link {
    color: #3699FF;
    border: 1px solid #E4E6EF;
    margin: 0 2px;
    border-radius: 4px !important;
    min-width: 40px;
    text-align: center;
}
.page-link:hover {
    color: #187DE4;
    background-color: #F1FAFF;
    border-color: #3699FF;
}
.page-item.active .page-link {
    background-color: #3699FF;
    border-color: #3699FF;
    color: white;
}
.page-item.disabled .page-link {
    color: #B5B5C3;
    background-color: #F5F8FA;
    border-color: #E4E6EF;
}
.border-top {
    border-top: 1px solid #E4E6EF !important;
}
</style>
