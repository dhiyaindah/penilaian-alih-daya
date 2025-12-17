@extends('layouts.public')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Konfirmasi Penilaian
</li>
@endsection

@section('content')
<div class="container py-4">

    <!-- Card Konfirmasi -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    
                    <!-- Header dengan Ikon -->
                    <div class="text-center mb-5">
                        <div class="icon-success mb-4">
                            <div class="checkmark-circle">
                                <div class="checkmark draw"></div>
                            </div>
                        </div>
                        
                        <h1 class="display-6 fw-bold text-success mb-3">
                            <i class="bx bx-check-circle"></i> Penilaian Berhasil Disimpan!
                        </h1>
                        
                        <p class="lead text-muted">
                            Terima kasih telah meluangkan waktu untuk mengisi penilaian kinerja.
                        </p>
                    </div>

                    <!-- Informasi Penilaian -->
                    <div class="alert alert-success border-0 bg-success-subtle mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bx bx-info-circle fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">Penilaian Anda Telah Terekam</h6>
                                <p class="mb-0">
                                    Data penilaian untuk <strong>Periode 2025</strong> 
                                    telah berhasil disimpan ke dalam sistem.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Data -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-clipboard me-2"></i> Ringkasan Penilaian
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-square bg-primary-subtle text-primary rounded-2 me-3">
                                            <i class="bx bx-calendar-check fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Tanggal Submit</small>
                                            <strong>{{ now()->format('d F Y, H:i') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-square bg-info-subtle text-info rounded-2 me-3">
                                            <i class="bx bx-user-check fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Jumlah Pegawai Dinilai</small>
                                            <strong>{{ $jumlahDinilai ?? 0 }} orang</strong>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-square bg-success-subtle text-success rounded-2 me-3">
                                            <i class="bx bx-bar-chart-alt-2 fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Periode Penilaian</small>
                                            <strong>Tahun 2025</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-square bg-warning-subtle text-warning rounded-2 me-3">
                                            <i class="bx bx-id-card fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Penilai</small>
                                            <strong>{{ session('nama_penilai') ?? 'Nama Penilai' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Footer -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary text-white">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-2">
                        <i class="bx bx-heart me-2"></i> Terima Kasih Atas Kontribusinya!
                    </h5>
                    <p class="mb-0 opacity-75">
                        Bersama kita wujudkan lingkungan kerja yang lebih produktif dan profesional.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Animasi Checkmark */
    .checkmark-circle {
        width: 100px;
        height: 100px;
        position: relative;
        margin: 0 auto;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
    }

    .checkmark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .checkmark.draw {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: draw 1s ease-in-out forwards;
        stroke: white;
        stroke-width: 4;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    @keyframes draw {
        to {
            stroke-dashoffset: 0;
        }
    }

    /* Icon Styles */
    .icon-square {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white !important;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .stat-icon {
        color: #696cff;
    }

    .icon-thanks {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    /* Gradient Background */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Print Styles */
    @media print {
        .btn, .no-print {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        
        .text-success {
            color: #000 !important;
        }
        
        .bg-light {
            background-color: #f8f9fa !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem !important;
            font-size: 1rem !important;
        }
        
        .display-6 {
            font-size: 2rem !important;
        }
        
        .checkmark-circle {
            width: 80px;
            height: 80px;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .stat-box {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animasi checkmark
    const checkmark = document.querySelector('.checkmark.draw');
    if (checkmark) {
        setTimeout(() => {
            checkmark.style.strokeDasharray = '1000';
            checkmark.style.strokeDashoffset = '1000';
            setTimeout(() => {
                checkmark.style.animation = 'draw 1s ease-in-out forwards';
            }, 300);
        }, 500);
    }

    // Count-up animation untuk statistik
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    }

    // Animasikan angka statistik
    const statNumbers = document.querySelectorAll('.fw-bold');
    statNumbers.forEach(stat => {
        const text = stat.textContent;
        if (text.includes('menit') && !isNaN(parseInt(text))) {
            const target = parseInt(text);
            animateCounter(stat, target, 1500);
        }
    });

    // Confetti effect
    function showConfetti() {
        if (typeof confetti === 'function') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
            
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                confetti({
                    particleCount: 50,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });
            }, 250);
        }
    }

    // Tampilkan confetti setelah delay
    setTimeout(showConfetti, 1000);

    // Auto-redirect setelah beberapa detik (opsional)
    let inactivityTimer;
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            window.location.href = "{{ route('dashboard') }}";
        }, 300000); // 5 menit
    }

    // Reset timer pada interaksi user
    document.addEventListener('mousemove', resetInactivityTimer);
    document.addEventListener('keypress', resetInactivityTimer);
    
    // Mulai timer
    resetInactivityTimer();
});
</script>

<!-- Confetti Library (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
@endpush