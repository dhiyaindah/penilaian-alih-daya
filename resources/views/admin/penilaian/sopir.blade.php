@extends('layouts.admin')

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

<form method="POST" action="{{ route('penilaian.store','sopir') }}">
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
        <div class="col-6 col-md-3 col-lg-2 text-center">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <img src="{{ $pegawai->foto
                        ? asset('storage/'.$pegawai->foto)
                        : 'https://ui-avatars.com/api/?name='.urlencode($pegawai->nama) }}"
                        class="rounded mb-2"
                        style="width:120px;height:120px;object-fit:cover">
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

{{-- ================= TABEL PENILAIAN ================= --}}
<div class="card border-0 shadow-sm">
<div class="card-body">

<table class="table table-bordered align-middle">
<thead class="table-light text-center">
<tr>
    <th width="25%">Nama Pegawai</th>
    <th width="25%">Nilai</th>
    <th width="50%">Catatan</th>
</tr>
</thead>
<tbody>
@foreach($pegawais as $pegawai)
<tr>
    <td class="fw-semibold">{{ $pegawai->nama }}</td>
    <td>
        <div class="d-flex justify-content-center gap-3">
            @for($i=1;$i<=5;$i++)
            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="skor[{{ $pegawai->id }}]"
                       value="{{ $i }}">
                <label class="form-check-label">{{ $i }}</label>
            </div>
            @endfor
        </div>
    </td>
    <td>
        <textarea class="form-control form-control-sm"
                  name="catatan[{{ $pegawai->id }}]"
                  rows="2"></textarea>
    </td>
</tr>
@endforeach
</tbody>
</table>

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
            Hapus
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

{{-- ================= TOMBOL ================= --}}
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

{{-- ================= SCRIPT (FIX FINAL) ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================= CANVAS ================= */
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

    /* ================= CEK CANVAS KOSONG (ANTI BOBOB) ================= */
    function isCanvasBlank(canvas) {
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

    /* ================= MODE TOGGLE ================= */
    const drawArea = document.getElementById('draw-area');
    const typeArea = document.getElementById('type-area');
    const ttdType = document.getElementById('ttd_type');

    document.querySelectorAll('input[name="ttd_mode"]').forEach(radio => {
        radio.addEventListener('change', function () {
            clearSignature(); // 🔥 RESET WAJIB

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

    /* ================= VALIDASI FORM ================= */
    const submitBtn = document.querySelector('button[value="next"]');

    submitBtn.addEventListener('click', function (e) {

        /* --- VALIDASI NILAI --- */
        const radioGroups = new Set();
        document.querySelectorAll('input[name^="skor["]').forEach(r => {
            radioGroups.add(r.name);
        });

        for (const name of radioGroups) {
            if (!document.querySelector(`input[name="${name}"]:checked`)) {
                alert('Harap isi semua nilai untuk setiap pegawai sebelum melanjutkan.');
                e.preventDefault();
                return;
            }
        }

        /* --- VALIDASI TTD --- */
        const mode = document.querySelector('input[name="ttd_mode"]:checked').value;

        if (mode === 'draw') {
            if (isCanvasBlank(canvas)) {
                alert('Tanda tangan belum digambar.');
                e.preventDefault();
                return;
            }
        }

        if (mode === 'type') {
            if (!ttdType.value.trim()) {
                alert('Harap isi nama penilai.');
                e.preventDefault();
                return;
            }
        }

    });

});
</script>
@endsection
