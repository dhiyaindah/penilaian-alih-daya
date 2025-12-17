@extends('layouts.public')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Penilaian Kinerja Pegawai Alih Daya
</li>
@endsection

@section('content')
<div class="container">

{{-- ================= HEADER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-center gap-4 flex-wrap">
            <img src="{{ asset('admin/Logo-Kemdikbud.png') }}" style="height:90px">

            <div class="text-center">
                <h6 class="fw-bold mb-1 text-uppercase text-secondary">
                    Kementerian Pendidikan Dasar dan Menengah
                </h6>
                <h6 class="fw-bold mb-2 text-uppercase text-secondary">
                    Republik Indonesia
                </h6>

                <h3 class="fw-bold text-primary mb-2">
                    Penilaian Alih Daya Tahun 2025
                </h3>

                <div class="fw-semibold">
                    Badan Pengembangan dan Pembinaan Bahasa
                </div>
                <div class="text-muted">
                    Balai Bahasa Provinsi Jawa Tengah
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $path = request()->path(); 
    // contoh: formulir_penilaian/kebersihan

    if (str_contains($path, 'kebersihan')) {
        $section = 'kebersihan';
    } elseif (str_contains($path, 'taman')) {
        $section = 'taman';
    } elseif (str_contains($path, 'keamanan')) {
        $section = 'keamanan';
    } elseif (str_contains($path, 'sopir')) {
        $section = 'sopir';
    } else {
        $section = 'kebersihan'; // default
    }

    $steps = ['kebersihan', 'taman', 'keamanan', 'sopir'];
    $labels = [
        'kebersihan' => 'Kebersihan',
        'taman' => 'Taman',
        'keamanan' => 'Keamanan',
        'sopir' => 'Sopir',
    ];

    $currentIndex = array_search($section, $steps);
    $progress = (($currentIndex + 1) / count($steps)) * 100;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-2">
            @foreach ($steps as $i => $step)
                <div class="text-center flex-fill">
                    <div class="fw-semibold 
                        {{ $i <= $currentIndex ? 'text-primary' : 'text-muted' }}">
                        {{ $labels[$step] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-primary"
                 role="progressbar"
                 style="width: {{ $progress }}%">
            </div>
        </div>

        <small class="text-muted d-block mt-2 text-center">
            Tahap {{ $currentIndex + 1 }} dari {{ count($steps) }}
        </small>

    </div>
</div>

<form method="POST" action="{{ route('public.penilaian.store','sopir') }}">
@csrf
<input type="hidden" name="current_section" value="sopir">

{{-- ================= FOTO PEGAWAI ================= --}}
@if($pegawais->count())
<div class="mb-5">
    <h5 class="fw-bold text-center mb-3">
        <span class="badge bg-primary">Bidang Sopir</span>
    </h5>

    <div class="row g-4 justify-content-center">
        @foreach($pegawais as $pegawai)
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card border-0 shadow-sm h-100 text-center pegawai-card">
                <div class="card-body p-3 d-flex flex-column align-items-center">

                    <div class="foto-frame mb-3">
                        <img
                            src="{{ $pegawai->foto
                                ? asset('storage/'.$pegawai->foto)
                                : 'https://ui-avatars.com/api/?name='.urlencode($pegawai->nama).'&background=6c757d&color=ffffff&size=256' }}"
                            alt="{{ $pegawai->nama }}">
                    </div>

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

{{-- ================= KRITERIA ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Kriteria Penilaian</h5>

        <ol>
            <li>Kualitas Kerja: Kemampuan menyelesaikan tugas dengan baik dan akurat.</li>
            <li>Kuantitas Kerja: Jumlah pekerjaan yang diselesaikan dalam waktu yang ditentukan.</li>
            <li>Kedisiplinan: Kedisiplinan dalam menjalankan tugas dan mengikuti aturan.</li>
            <li>Kerja sama: Kemampuan bekerja sama dengan rekan kerja dan tim.</li>
            <li>Inisiatif: Kemampuan mengambil inisiatif dan memecahkan masalah.</li>
            <li>Komunikasi: Kemampuan berkomunikasi efektif dengan rekan kerja dan atasan.</li>
            <li>Pengembangan Diri: Kemampuan meningkatkan kemampuan dan pengetahuan diri.</li>
            <li>Loyalitas: Kesetiaan dan komitmen terhadap organisasi.</li>
        </ol>

        <div class="alert alert-light border mt-3">
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

{{-- ================= TABEL PENILAIAN ================= --}}
<div class="table-responsive">
    <table class="table table-bordered align-middle table-white">
        <thead class="table-light text-center">
            <tr>
                <th style="min-width: 150px;">Nama Pegawai</th>
                <th style="min-width: 180px;">Nilai</th>
                <th>Kritik / Saran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $pegawai)
            <tr>
                <td class="fw-semibold">
                    <div class="d-flex align-items-center">
                        <div>
                            <strong>{{ $pegawai->nama }}</strong>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="rating-container">
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-check rating-option">
                                    <input
                                        class="form-check-input rating-input"
                                        type="radio"
                                        name="skor[{{ $pegawai->id }}]"
                                        id="rating_{{ $pegawai->id }}_{{ $i }}"
                                        value="{{ $i }}"
                                        {{ (isset($data['skor'][$pegawai->id]) && $data['skor'][$pegawai->id] == $i) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label rating-label"
                                        for="rating_{{ $pegawai->id }}_{{ $i }}">
                                        <span class="rating-number">{{ $i }}</span>
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </td>

                <td>
                    <div class="comment-container">
                        <textarea
                            class="form-control comment-textarea"
                            name="catatan[{{ $pegawai->id }}]"
                            rows="2"
                            placeholder="Berikan catatan, kritik, atau saran..."
                        >{{ $data['catatan'][$pegawai->id] ?? '' }}</textarea>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ================= TANDA TANGAN ================= --}}
<div class="card border-0 shadow-sm mt-4">
<div class="card-body">
<h5 class="fw-bold mb-3">Tanda Tangan Penilai</h5>

{{-- MODE --}}
<div class="mb-3">
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio"
               name="ttd_mode" value="draw" checked>
        <label class="form-check-label">Gambar</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio"
               name="ttd_mode" value="type">
        <label class="form-check-label">Ketik Nama</label>
    </div>
</div>

{{-- DRAW --}}
<div id="draw-area">
    <canvas id="signature-pad" width="400" height="150"
        style="border:1px solid #ccc;border-radius:6px"></canvas>
    <input type="hidden" name="tanda_tangan_draw" id="ttd_draw">

    <div class="mt-2">
        <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="clearSignature()">
            Hapus Tanda Tangan
        </button>
    </div>
</div>

{{-- TYPE --}}
<div id="type-area" class="d-none mt-3">
    <input type="text"
           class="form-control w-50"
           name="tanda_tangan_type"
           id="ttd_type"
           placeholder="Nama lengkap penilai">
</div>

</div>
</div>

{{-- ================= NAVIGASI ================= --}}
<div class="d-flex justify-content-between mt-4">
    <button type="submit" name="action" value="prev"
            class="btn btn-secondary">
        Sebelumnya
    </button>

    <button type="submit" name="action" value="next"
            class="btn btn-primary">
        Simpan Penilaian
    </button>
</div>

</div>
</div>
</form>
</div>

{{-- ================= VALIDASI ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.querySelector('button[name="action"][value="next"]');

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            let valid = true;
            const radioNames = new Set();

            document.querySelectorAll('input[name^="skor["]').forEach(r => {
                radioNames.add(r.name);
            });

            for (const name of radioNames) {
                if (!document.querySelector(`input[name="${name}"]:checked`)) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                e.preventDefault();
                alert('Harap isi semua nilai untuk setiap pegawai sebelum melanjutkan.');
            }
        });
    }
});
</script>

<script>
document.getElementById('penilai_id')?.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    document.getElementById('nip_penilai').value =
        selected.getAttribute('data-nip') || '';
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    function pos(e){
        const r = canvas.getBoundingClientRect();
        return {
            x:(e.touches ? e.touches[0].clientX : e.clientX) - r.left,
            y:(e.touches ? e.touches[0].clientY : e.clientY) - r.top
        };
    }

    function start(e){
        drawing = true;
        ctx.beginPath();
        const p = pos(e);
        ctx.moveTo(p.x, p.y);
    }

    function draw(e){
        if(!drawing) return;
        e.preventDefault();
        const p = pos(e);
        ctx.lineTo(p.x, p.y);
        ctx.strokeStyle = "#000";
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.stroke();
    }

    function stop(){
        if(!drawing) return;
        drawing = false;
        document.getElementById('ttd_draw').value = canvas.toDataURL();
    }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', start);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stop);

    window.clearSignature = function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('ttd_draw').value = '';
    };

    function isCanvasBlank(canvas) {
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

    const drawArea = document.getElementById('draw-area');
    const typeArea = document.getElementById('type-area');
    const ttdType = document.getElementById('ttd_type');

    document.querySelectorAll('input[name="ttd_mode"]').forEach(radio => {
        radio.addEventListener('change', function () {
            clearSignature();
            if (this.value === 'draw') {
                drawArea.classList.remove('d-none');
                typeArea.classList.add('d-none');
                ttdType.value = '';
            } else {
                typeArea.classList.remove('d-none');
                drawArea.classList.add('d-none');
            }
        });
    });

    document.querySelector('button[value="next"]').addEventListener('click', function(e){

        const radioNames = new Set();
        document.querySelectorAll('input[name^="skor["]').forEach(r => {
            radioNames.add(r.name);
        });

        for (const name of radioNames) {
            if (!document.querySelector(`input[name="${name}"]:checked`)) {
                alert('Harap isi semua nilai untuk setiap pegawai.');
                e.preventDefault();
                return;
            }
        }

        const mode = document.querySelector('input[name="ttd_mode"]:checked').value;

        if (mode === 'draw' && isCanvasBlank(canvas)) {
            alert('Tanda tangan belum digambar.');
            e.preventDefault();
        }

        if (mode === 'type' && !ttdType.value.trim()) {
            alert('Nama penilai wajib diisi.');
            e.preventDefault();
        }
    });
});
</script>

{{-- ================= STYLE ================= --}}
<style>
.pegawai-card { min-height: 240px; }

.foto-frame {
    width: 120px;
    height: 150px;
    background: #f1f3f5;
    border-radius: 8px;
    overflow: hidden;
}

.foto-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.form-check-input,
.form-check-label { cursor:pointer; }

textarea { resize: vertical; }

.table-white tbody tr {
    background-color: #fff !important;
}

/* Responsive design */
@media (max-width: 992px) {
    .table-responsive {
        margin: 0 -15px;
    }
    
    table {
        min-width: 800px;
    }
    
    .comment-textarea {
        min-height: 90px;
        font-size: 13px;
        padding: 10px;
    }
    
    .rating-label {
        padding: 6px 10px;
        min-width: 40px;
    }
    
    .rating-star {
        font-size: 18px;
    }
}

@media (max-width: 768px) {
    .comment-textarea {
        min-height: 80px;
        font-size: 12px;
    }
    
    .rating-label {
        padding: 5px 8px;
        min-width: 35px;
    }
    
    .rating-star {
        font-size: 16px;
    }
    
    .rating-number {
        font-size: 12px;
    }
    
    .avatar-sm {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
}

@media (max-width: 576px) {
    .d-flex.justify-content-center.gap-2 {
        gap: 4px !important;
    }
    
    .comment-textarea {
        min-height: 70px;
        padding: 8px;
    }
    
    .rating-label {
        padding: 4px 6px;
        min-width: 30px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textareas = document.querySelectorAll('.comment-textarea');
    
    textareas.forEach(textarea => {
        // Auto height berdasarkan konten
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Set initial height
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
        
        // Placeholder template dengan nama pegawai
        const row = textarea.closest('tr');
        const namaPegawai = row.querySelector('.fw-semibold strong').textContent;
        textarea.setAttribute('placeholder', 
            `Berikan catatan, kritik, atau saran...`
        );
    });
    
    // Interaksi rating
    const ratingInputs = document.querySelectorAll('.rating-input');
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const star = label.querySelector('.rating-star i');
            const value = parseInt(this.value);
            
            // Update icon berdasarkan nilai
            if (value <= 3) {
                star.className = 'bx bx-star';
                star.style.color = '#ffc107';
            } else {
                star.className = 'bx bxs-star';
                star.style.color = '#28a745';
            }
        });
        
        // Trigger change untuk rating yang sudah terpilih
        if (input.checked) {
            input.dispatchEvent(new Event('change'));
        }
    });
    
    // Set tooltip untuk rating
    const ratingOptions = document.querySelectorAll('.rating-option');
    const tooltips = {
        1: "Perlu Perbaikan Signifikan",
        2: "Perlu Perbaikan",
        3: "Cukup",
        4: "Baik",
        5: "Sangat Baik"
    };
    
    ratingOptions.forEach(option => {
        const input = option.querySelector('.rating-input');
        if (input) {
            option.setAttribute('data-tooltip', tooltips[input.value] || '');
        }
    });
});
</script>
@endsection
