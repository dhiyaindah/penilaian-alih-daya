@extends('layouts.admin')

@section('content')

<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-primary mb-1">Sistem Penilaian Kinerja Alih Daya</h4>
                    <p class="mb-0">Selamat datang, User Testing</p>
                </div>
                <img src="{{ asset('admin/img/illustrations/man-with-laptop-light.png') }}" height="110" />
            </div>
        </div>
    </div>
</div>

{{-- Ringkasan --}}


<style>
    .info-card {
        position: relative;
        padding: 15px 20px;
        border-radius: 8px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .info-card .icon-circle {
        position: absolute;
        top: -15px;
        left: -10px;
        background: #4e5c66;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .info-card .count-box {
        background: white;
        color: black;
        padding: 3px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 14px;
        margin-left: 10px;
    }

    .label-text {
        font-size: 16px;
        font-weight: 500;
        margin-left: 20px;
    }
</style>

@endsection
