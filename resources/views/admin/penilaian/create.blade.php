@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penilaian.index') }}">Penilaian Kinerja</a></li>
    <li class="breadcrumb-item active text-primary fw-semibold">Beri Penilaian</li>
@endsection

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Beri Penilaian</h5>
                        <small class="text-muted">Isi form penilaian untuk pegawai alih daya berikut</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('penilaian.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Employee Information Card -->
                <div class="card-body border-bottom">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <!-- Profile Photo -->
                                <div class="symbol symbol-80px symbol-circle me-4">
                                    <img src="{{ $alih_daya->foto 
                                            ? asset('storage/' . $alih_daya->foto) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($alih_daya->nama) . '&background=3699FF&color=ffffff&size=128' }}"
                                         class="rounded-circle border border-3 border-white shadow-sm"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         alt="{{ $alih_daya->nama }}">
                                </div>
                                
                                <!-- Employee Details -->
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">{{ $alih_daya->nama }}</h4>
                                    <div class="d-flex flex-wrap gap-3 mb-2">
                                        @if($alih_daya->nip)
                                        <div class="text-muted">
                                            <i class="bx bx-id-card me-1"></i> NIP: {{ $alih_daya->nip }}
                                        </div>
                                        @endif
                                        <div class="text-muted">
                                            <i class="bx bx-briefcase me-1"></i> {{ $alih_daya->jabatan ?? 'alih_daya Alih Daya' }}
                                        </div>
                                        @if($alih_daya->unit_kerja)
                                        <div class="text-muted">
                                            <i class="bx bx-buildings me-1"></i> {{ $alih_daya->unit_kerja }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bx bx-calendar me-1"></i> Periode Penilaian: 
                                        <span class="fw-semibold text-dark">{{ date('F Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="card bg-light-primary border-primary border-2">
                                <div class="card-body p-3">
                                    <div class="text-muted small mb-1">Total Skor Maksimal</div>
                                    <div class="fw-bold fs-3 text-primary">{{ $totalSkor }}</div>
                                    <div class="text-muted small">
                                        {{ count($kriterias) }} Kriteria Penilaian
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Form -->
                <form action="{{ route('penilaian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="alih_daya_id" value="{{ $alih_daya->id }}">
                    
                    <div class="card-body">
                        <!-- General Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Penilai</label>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ Auth::user()->name }}" readonly>
                                    <input type="hidden" name="penilai_id" value="{{ Auth::id() }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jabatan</label>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ Auth::user()->pegawai->jabatan }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Rating Criteria Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bx bx-clipboard text-primary me-2"></i>
                                Kriteria Penilaian
                            </h6>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="25%">Kriteria</th>
                                            <th width="25%">Nilai</th>
                                            <th colspan="2" width="30%">Catatan / Bukti / Temuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $index => $kriteria)
                                        <tr>
                                            <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $kriteria->nama }}</div>
                                                @if($kriteria->keterangan)
                                                <small class="text-muted">{{ $kriteria->keterangan }}</small>
                                                @endif
                                                <input type="hidden" name="kriteria_id[]" value="{{ $kriteria->id }}">
                                            </td>
                                            
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" 
                                                        name="nilai[]" 
                                                        class="form-control nilai-input @error('nilai.' . $index) is-invalid @enderror"
                                                        min="0" 
                                                        max="{{ $kriteria->skor_maks }}"  
                                                        step="1"
                                                        value="{{ old('nilai.' . $index, 0) }}"
                                                        oninput="validateScore(this, {{ $kriteria->skor_maks }})"
                                                        data-skormaks="{{ $kriteria->skor_maks }}"
                                                        required>
                                                    <span class="input-group-text">/ {{ $kriteria->skor_maks }}</span>
                                                    @error('nilai.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td colspan="2" >
                                                <textarea name="catatan[]" 
                                                          class="form-control @error('catatan.' . $index) is-invalid @enderror"
                                                          rows="2"
                                                          placeholder="Masukkan catatan, bukti, atau temuan untuk kriteria ini...">{{ old('catatan.' . $index) }}</textarea>
                                                @error('catatan.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2" class="text-end fw-bold">Total</td>
                                            <!-- <td class="text-center fw-bold">{{ $totalSkor }}%</td> -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" id="total-nilai" class="form-control bg-light fw-bold text-primary text-center" 
                                                           value="0" readonly>
                                                    <span class="input-group-text bg-light fw-bold">/ {{ $totalSkor }}</span>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end fw-bold">Nilai Akhir</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" id="nilai-akhir" class="form-control bg-light fw-bold fs-4 text-primary text-center" 
                                                           value="0.00" readonly>
                                                    <span class="input-group-text bg-light fw-bold">/ 100</span>
                                                </div>
                                            </td>
                                            <td colspan="2" class="text-end">
                                                <div id="grade" class="badge bg-primary fs-6 px-3 py-2">-</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Rekomendasi Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bx bx-bulb text-warning me-2"></i>
                                Rekomendasi
                            </h6>
                            
                            <!-- Radio Button Options -->
                            <div class="card border">
                                <div class="card-body p-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="rekomendasi" 
                                            id="rekomendasi1" value="dilanjutkan" 
                                            {{ old('rekomendasi') == 'dilanjutkan' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="rekomendasi1">
                                            Dilanjutkan
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="rekomendasi" 
                                            id="rekomendasi2" value="perlu_pembinaan" 
                                            {{ old('rekomendasi') == 'perlu_pembinaan' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="rekomendasi2">
                                            Perlu pembinaan
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="rekomendasi" 
                                            id="rekomendasi3" value="penggantian_tenaga" 
                                            {{ old('rekomendasi') == 'penggantian_tenaga' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="rekomendasi3">
                                            Penggantian tenaga
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="rekomendasi" 
                                            id="rekomendasi4" value="evaluasi_kontrak" 
                                            {{ old('rekomendasi') == 'evaluasi_kontrak' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="rekomendasi4">
                                            Evaluasi kontrak dengan vendor
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="rekomendasi" 
                                            id="rekomendasi5" value="rekomendasi_lain" 
                                            {{ old('rekomendasi') == 'rekomendasi_lain' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="rekomendasi5">
                                            Rekomendasi lain:
                                        </label>
                                        
                                        <!-- Input untuk rekomendasi lain -->
                                        <div class="mt-2 ms-4" id="rekomendasiLainContainer" 
                                            style="{{ old('rekomendasi') == 'rekomendasi_lain' ? '' : 'display: none;' }}">
                                            <textarea class="form-control @error('rekomendasi_lain_teks') is-invalid @enderror" 
                                                    id="rekomendasi_lain_teks" name="rekomendasi_lain_teks" 
                                                    rows="2" placeholder="Tuliskan rekomendasi lainnya...">{{ old('rekomendasi_lain_teks') }}</textarea>
                                            @error('rekomendasi_lain_teks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @error('rekomendasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="bx bx-info-circle me-1"></i>
                            Pastikan semua data telah diisi dengan benar
                        </div>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-secondary">
                                <i class="bx bx-reset me-2"></i>Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-2"></i>Simpan Penilaian
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Perhitungan Nilai -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
        
        // Auto-calculate when nilai inputs change
        const nilaiInputs = document.querySelectorAll('.nilai-input');
        nilaiInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        
        // Format tanggal untuk hari ini
        document.getElementById('tanggal_penilaian').valueAsDate = new Date();
    });

    function calculateTotal() {
        let totalNilai = 0;
        let totalSkor = {{ $totalSkor }}
        let totalNilaiAkhir = 0;
        
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const nilaiInput = row.querySelector('.nilai-input');
            
            if (nilaiInput) {
                const nilai = parseFloat(nilaiInput.value) || 0;
                
                totalNilai += nilai;
                totalNilaiAkhir += (nilai / totalSkor) * 100;
            }
        });
        
        // Update display
        document.getElementById('total-nilai').value = Math.round(totalNilai);
        document.getElementById('nilai-akhir').value = totalNilaiAkhir.toFixed(2);
        
        // Update grade
        updateGrade(totalNilaiAkhir);
    }

    function updateGrade(nilai) {
        const gradeElement = document.getElementById('grade');
        let grade = '';
        let color = '';
        
        if (nilai >= 90) {
            grade = 'A (Sangat Baik)';
            color = 'bg-success';
        } else if (nilai >= 75) {
            grade = 'B (Baik)';
            color = 'bg-primary';
        } else if (nilai >= 60) {
            grade = 'C (Cukup)';
            color = 'bg-info';
        } else if (nilai >= 50) {
            grade = 'D (Kurang)';
            color = 'bg-warning';
        } else {
            grade = 'E (Tidak Memuaskan)';
            color = 'bg-danger';
        }
        
        gradeElement.textContent = grade;
        gradeElement.className = `badge ${color} fs-6 px-3 py-2`;
    }
    
    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nilaiInputs = document.querySelectorAll('.nilai-input');
        let isValid = true;
        
        nilaiInputs.forEach(input => {
            const nilai = parseFloat(input.value);
            if (isNaN(nilai) || nilai < 0 || nilai > 100) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa nilai yang dimasukkan. Nilai harus antara 0-100.');
        }
    });
    </script>
    <script>
    // Toggle input rekomendasi lain
    document.addEventListener('DOMContentLoaded', function() {
        const rekomendasiLainRadio = document.getElementById('rekomendasi5');
        const rekomendasiLainContainer = document.getElementById('rekomendasiLainContainer');
        
        // Event listener untuk semua radio button rekomendasi
        document.querySelectorAll('input[name="rekomendasi"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'rekomendasi_lain') {
                    rekomendasiLainContainer.style.display = 'block';
                    document.getElementById('rekomendasi_lain_teks').focus();
                } else {
                    rekomendasiLainContainer.style.display = 'none';
                    document.getElementById('rekomendasi_lain_teks').value = '';
                }
            });
        });
        
        // Set initial state based on old input
        if (rekomendasiLainRadio.checked) {
            rekomendasiLainContainer.style.display = 'block';
        }
        
        // Validasi sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedRekomendasi = document.querySelector('input[name="rekomendasi"]:checked');
            const rekomendasiLainText = document.getElementById('rekomendasi_lain_teks').value.trim();
            
            if (!selectedRekomendasi) {
                e.preventDefault();
                showAlert('error', 'Harap pilih salah satu rekomendasi');
                return false;
            }
            
            // Validasi jika pilih rekomendasi lain tapi tidak diisi
            if (selectedRekomendasi.value === 'rekomendasi_lain' && !rekomendasiLainText) {
                e.preventDefault();
                document.getElementById('rekomendasi_lain_teks').focus();
                showAlert('error', 'Harap isi rekomendasi lainnya');
                return false;
            }
        });
    });

    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'error' ? 'Perhatian!' : 'Berhasil',
            text: message,
            confirmButtonText: 'OK'
        });
    }
    </script>
    <script>
function validateScore(input, max) {
    let val = parseInt(input.value);

    // Jika bukan angka → set 0
    if (isNaN(val)) {
        input.value = 0;
        return;
    }

    // Cegah angka negatif
    if (val < 0) {
        input.value = 0;
        return;
    }

    // Cegah melebihi skor_maks
    if (val > max) {
        input.value = max;
        return;
    }

    // Cegah angka desimal
    input.value = Math.floor(val);
}
</script>


    <style>
    .symbol {
        display: inline-block;
        flex-shrink: 0;
        position: relative;
        border-radius: 0.475rem;
    }
    .symbol-80px {
        width: 80px !important;
        height: 80px !important;
    }
    .symbol-circle {
        border-radius: 50%;
    }
    
    table th {
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #181C32;
    }
    
    .table-bordered {
        border: 1px solid #E4E6EF;
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #E4E6EF;
        vertical-align: middle;
    }
    
    .form-control.bg-light {
        background-color: #f8f9fa !important;
        border-color: #E4E6EF;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #E4E6EF;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #E4E6EF;
    }
    
    .card-footer {
        border-top: 1px solid #E4E6EF;
    }
    
    .badge.bg-light-primary {
        background-color: rgba(54, 153, 255, 0.1) !important;
        color: #3699FF !important;
    }
    
    #nilai-akhir {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .form-check-label .badge {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    
    .form-check-label small {
        font-size: 0.75rem;
    }
    </style>
@endsection