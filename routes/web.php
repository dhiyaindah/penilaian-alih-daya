<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\TimAlihDayaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('loginAction');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerAction'])->name('registerAction');
});

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('alih_daya', TimAlihDayaController::class);
    Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::post('/alih-daya/import', [TimAlihDayaController::class, 'import'])->name('alih_daya.import');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // PENILAIAN
    Route::prefix('penilaian')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('/rekap', [PenilaianController::class, 'rekap'])->name('penilaian.rekap');
        Route::get('/create/{id}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/{penilaian}', [PenilaianController::class, 'show'])->name('penilaian.show');
        Route::get('/{penilaian}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
    });
});

// routes/web.php

