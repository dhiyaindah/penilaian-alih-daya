@extends('layouts.auth')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">

        <!-- Header dalam Card -->
        <div class="text-center mb-4">
            <div class="mb-2">
                <i class="bx bx-user-circle display-4 text-primary"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
            <p class="text-muted mb-0">Silakan login untuk mengakses sistem penilaian tenaga alih daya</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4">
            <i class="bx bx-check-circle me-2 fs-4"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4">
            <i class="bx bx-error-circle me-2 fs-4"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="bx bx-error-circle me-2 fs-4"></i>
                <h6 class="mb-0 fw-semibold">Login Gagal</h6>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Login Form -->
        <form id="formLogin" action="{{ route('loginAction') }}" method="POST">
            @csrf
            
            <!-- Username/Email/NIP -->
            <div class="mb-4">
                <label for="login" class="form-label fw-semibold">Email atau NIP <span class="text-danger">*</span></label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                    <input type="text"
                           class="form-control @error('login') is-invalid @enderror"
                           id="login"
                           name="login"
                           value="{{ old('login') }}"
                           placeholder="Masukkan email atau NIP"
                           required
                           autofocus>
                </div>
                @error('login')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                    <input type="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           placeholder="Masukkan password"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bx bx-hide"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold py-2">
                    <i class="bx bx-log-in me-2"></i>Masuk
                </button>
            </div>
        </form>

        <!-- Register Link -->
        <div class="text-center pt-3 border-top">
            <p class="mb-0 text-muted">
                Belum punya akun?
                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar di sini</a>
            </p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.querySelector('i').className = type === 'password' ? 'bx bx-hide' : 'bx bx-show';
    });

    // Auto-close alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => new bootstrap.Alert(alert).close());
    }, 5000);
});
</script>

<style>
.card {
    border-radius: 12px;
}
.input-group-text {
    background-color: #f8f9fa;
}
</style>

@endsection
