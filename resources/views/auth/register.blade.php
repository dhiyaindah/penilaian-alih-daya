@extends('layouts.auth')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">

            <!-- Form + Header in One Card -->
            <div class="card">
                <div class="card-body p-4 p-md-5">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bx bx-user-plus display-5 text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Pendaftaran Akun</h3>
                        <p class="text-muted mb-0">Silakan lengkapi data diri Anda</p>
                    </div>

                    <!-- Form -->
                    <form id="formRegister" action="{{ route('registerAction') }}" method="POST">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Masukkan nama lengkap"
                                       autocomplete="off"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div class="mb-3">
                            <label for="nip" class="form-label fw-semibold">NIP <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                <input type="text" 
                                       class="form-control @error('nip') is-invalid @enderror"
                                       id="nip" 
                                       name="nip" 
                                       value="{{ old('nip') }}" 
                                       placeholder="Masukkan NIP"
                                       autocomplete="off"
                                       required>
                            </div>
                            @error('nip')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-male-female"></i></span>
                                <select name="jenis_kelamin" 
                                        class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        required>
                                    <option disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
                                </select>
                            </div>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-map"></i></span>
                                    <input type="text" 
                                           class="form-control @error('tempat_lahir') is-invalid @enderror"
                                           name="tempat_lahir" 
                                           value="{{ old('tempat_lahir') }}" 
                                           placeholder="Kota kelahiran"
                                           required>
                                </div>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="date" 
                                           class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                           name="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir') }}"
                                           required>
                                </div>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="nama@contoh.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                                <input type="text" 
                                       class="form-control @error('jabatan') is-invalid @enderror"
                                       name="jabatan" 
                                       value="{{ old('jabatan') }}" 
                                       placeholder="Masukkan jabatan"
                                       required>
                            </div>
                            @error('jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pangkat Golongan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pangkat/Golongan <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-award"></i></span>
                                <input type="text" 
                                       class="form-control @error('pangkat_golongan') is-invalid @enderror"
                                       name="pangkat_golongan" 
                                       value="{{ old('pangkat_golongan') }}" 
                                       placeholder="Contoh: Penata Muda, III-b">
                            </div>
                            @error('pangkat_golongan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                <input type="password" 
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" 
                                       placeholder="Minimal 8 karakter"
                                       required>
                                <span class="input-group-text cursor-pointer" id="togglePassword">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                            <small class="text-muted">Password minimal 8 karakter</small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary d-grid w-100 fw-semibold py-2">
                            Daftar
                        </button>

                        <!-- Login Link -->
                        <div class="text-center pt-3 border-top mt-3">
                            <p class="mb-0">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /Card End -->

        </div>
    </div>
</div>

@endsection
