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
                        <small class="text-muted">Isi form penilaian untuk alih_daya berikut</small>
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
                                    <div class="text-muted small mb-1">Total Bobot</div>
                                    <div class="fw-bold fs-3 text-primary">{{ $totalBobot }}%</div>
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
                                    <label for="tanggal_penilaian" class="form-label fw-semibold">
                                        Tanggal Penilaian <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_penilaian') is-invalid @enderror" 
                                           id="tanggal_penilaian" name="tanggal_penilaian" 
                                           value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required>
                                    @error('tanggal_penilaian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                            <th width="30%">Catatan / Bukti / Temuan</th>
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
                                            <td class="text-center">
                                                <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2">
                                                    {{ $kriteria->bobot }}%
                                                </span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           name="nilai[]" 
                                                           class="form-control nilai-input @error('nilai.' . $index) is-invalid @enderror"
                                                           min="0" 
                                                           max="100" 
                                                           step="1"
                                                           value="{{ old('nilai.' . $index, 0) }}"
                                                           oninput="calculateTotal()"
                                                           required>
                                                    <span class="input-group-text">/ 100</span>
                                                    @error('nilai.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
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
                                            <td class="text-center fw-bold">{{ $totalBobot }}%</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" id="total-nilai" class="form-control bg-light fw-bold text-primary text-center" 
                                                           value="0" readonly>
                                                    <span class="input-group-text bg-light fw-bold">/ 100</span>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end fw-bold">Nilai Akhir</td>
                                            <td colspan="2">
                                                <div class="d-flex align-items-center">
                                                    <input type="text" id="nilai-akhir" class="form-control bg-light fw-bold fs-4 text-primary text-center" 
                                                           value="0.00" readonly>
                                                    <span class="input-group-text bg-light fw-bold">/ 100</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div id="grade" class="badge bg-primary fs-6 px-3 py-2">-</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bx bx-note text-primary me-2"></i>
                                Informasi Tambahan
                            </h6>
                            
                            <!-- Strengths (Kelebihan) -->
                            <div class="mb-3">
                                <label for="kelebihan" class="form-label fw-semibold">
                                    <i class="bx bx-plus-circle text-success me-2"></i>
                                    Kelebihan / Prestasi
                                </label>
                                <textarea class="form-control @error('kelebihan') is-invalid @enderror" 
                                          id="kelebihan" name="kelebihan" rows="3" 
                                          placeholder="Tuliskan kelebihan, pencapaian, atau prestasi yang menonjol...">{{ old('kelebihan') }}</textarea>
                                @error('kelebihan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Weaknesses (Kekurangan) -->
                            <div class="mb-3">
                                <label for="kekurangan" class="form-label fw-semibold">
                                    <i class="bx bx-minus-circle text-danger me-2"></i>
                                    Kekurangan / Area Perbaikan
                                </label>
                                <textarea class="form-control @error('kekurangan') is-invalid @enderror" 
                                          id="kekurangan" name="kekurangan" rows="3" 
                                          placeholder="Tuliskan kekurangan atau area yang perlu diperbaiki...">{{ old('kekurangan') }}</textarea>
                                @error('kekurangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Recommendations (Rekomendasi) -->
                            <div class="mb-4">
                                <label for="rekomendasi" class="form-label fw-semibold">
                                    <i class="bx bx-bulb text-warning me-2"></i>
                                    Rekomendasi / Saran Pengembangan
                                </label>
                                <textarea class="form-control @error('rekomendasi') is-invalid @enderror" 
                                          id="rekomendasi" name="rekomendasi" rows="3" 
                                          placeholder="Tuliskan rekomendasi untuk pengembangan karir atau peningkatan kinerja...">{{ old('rekomendasi') }}</textarea>
                                @error('rekomendasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- General Notes -->
                        <div class="mb-4">
                            <label for="catatan_umum" class="form-label fw-semibold">
                                <i class="bx bx-message-detail text-info me-2"></i>
                                Catatan Umum Penilaian
                            </label>
                            <textarea class="form-control @error('catatan_umum') is-invalid @enderror" 
                                      id="catatan_umum" name="catatan_umum" rows="3" 
                                      placeholder="Catatan tambahan atau kesan umum terhadap kinerja alih_daya...">{{ old('catatan_umum') }}</textarea>
                            @error('catatan_umum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Assessment -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-task text-secondary me-2"></i>
                                Status Penilaian
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="status" 
                                               id="status_draft" value="draft" checked>
                                        <label class="form-check-label" for="status_draft">
                                            <span class="badge bg-secondary">Draft</span>
                                            <small class="text-muted d-block">Simpan sebagai draft untuk dilengkapi nanti</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="status" 
                                               id="status_selesai" value="selesai">
                                        <label class="form-check-label" for="status_selesai">
                                            <span class="badge bg-success">Selesai</span>
                                            <small class="text-muted d-block">Simpan sebagai penilaian final</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
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
        let totalBobot = 0;
        let totalNilaiAkhir = 0;
        
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const nilaiInput = row.querySelector('.nilai-input');
            const bobotElement = row.querySelector('.badge');
            
            if (nilaiInput && bobotElement) {
                const nilai = parseFloat(nilaiInput.value) || 0;
                const bobotText = bobotElement.textContent;
                const bobot = parseFloat(bobotText.replace('%', '')) || 0;
                
                totalNilai += nilai;
                totalBobot += bobot;
                totalNilaiAkhir += (nilai * bobot) / 100;
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
        } else if (nilai >= 80) {
            grade = 'B (Baik)';
            color = 'bg-primary';
        } else if (nilai >= 70) {
            grade = 'C (Cukup)';
            color = 'bg-info';
        } else if (nilai >= 60) {
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