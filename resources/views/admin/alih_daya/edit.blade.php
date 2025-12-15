@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('alih_daya.index') }}">Data Alih Daya</a></li>
    <li class="breadcrumb-item active text-primary fw-semibold">Edit Alih Daya</li>
@endsection
@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Edit Data Pegawai Alih Daya</h5>
                    <a href="{{ route('alih_daya.index', ['page' => request('page')]) }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('alih_daya.update', ['alih_daya' => $alih_daya->id, 'page' => request('page')]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Pegawai Alih Daya</label>
                            <div class="col-sm-10">
                                <input type="text"
                                    class="form-control @error('nama')
                                    is-invalid
                                @enderror"
                                    id="basic-default-name" name="nama" autocomplete="off"
                                    value="{{ $alih_daya->nama }}" />
                                @error('nama')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <select name="status" class="form-select">
                                    <option value="aktif" {{ old('status', $alih_daya->status ?? '') == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status', $alih_daya->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Jabatan</label>
                            <div class="col-sm-10">
                                <input type="text"
                                    class="form-control @error('jabatan')
                                    is-invalid
                                @enderror"
                                    id="basic-default-name" name="jabatan" autocomplete="off"
                                    value="{{ $alih_daya->jabatan }}" />
                                @error('jabatan')
                                    <div id="validationServer03Feedback" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Foto</label>
                            <div class="col-sm-10">

                                {{-- Tampilkan foto lama jika ada --}}
                                @if ($alih_daya->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $alih_daya->foto) }}"
                                            alt="Foto Alih Daya"
                                            class="img-thumbnail"
                                            style="max-width: 150px;">
                                    </div>
                                @endif

                                {{-- Upload foto baru --}}
                                <input type="file"
                                    class="form-control @error('foto') is-invalid @enderror"
                                    name="foto">
                                <div class="form-text">
                                    Hanya menerima file bertipe <strong>JPG, JPEG, PNG</strong>.  
                                    Maksimal ukuran file: <strong>10 MB</strong>.
                                </div>

                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
