<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\TimAlihDayaController;
use App\Http\Controllers\PegawaiPenilaianController;
use Illuminate\Support\Facades\Route;

Route::prefix('formulir_penilaian')->group(function () {

    Route::get('/', [PegawaiPenilaianController::class, 'index']);
    // Route::get('/keamanan', [PegawaiPenilaianController::class, 'keamanan']);
    // Route::get('/sopir', [PegawaiPenilaianController::class, 'sopir']);
    // Route::get('/taman', [PegawaiPenilaianController::class, 'taman']);

    // Route::post('/{section}', [PenilaianController::class, 'store'])->name('penilaian.pegawai.store');

    // SUBMIT FORM PEGAWAI (PUBLIC)
    Route::post('/{section}', [PegawaiPenilaianController::class, 'store'])->name('public.penilaian.store');
    Route::get('/{section}', [PegawaiPenilaianController::class, 'show'])->name('public.penilaian.section');
    Route::get('/terimakasih', [PegawaiPenilaianController::class, 'terimakasih'])->name('public.penilaian.terimakasih');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('loginAction');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerAction'])->name('registerAction');
});

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
Route::prefix('admin')->group(function () {
    Route::prefix('penilaian')->group(function () {
        Route::get('/halaman-2', function () {
            return view('admin.penilaian.index2');
        })->name('admin.penilaian.halaman2');
    });
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
        Route::get('/halaman-2', [PenilaianController::class, 'index2'])->name('admin.penilaian.index2');
        Route::get('/halaman-3', [PenilaianController::class, 'index3'])->name('admin.penilaian.index3');
        Route::get('/halaman-4', [PenilaianController::class, 'index4'])->name('admin.penilaian.index4');
        Route::get('/rekap', [PenilaianController::class, 'rekap'])->name('penilaian.rekap');
        Route::get('/penilaian/detail', [PenilaianController::class, 'detail'])->name('penilaian.detail');
        Route::get('/create/{id}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/{section}', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/{section}', [PenilaianController::class, 'show'])->name('penilaian.section');
        // Route::get('/{penilaian}', [PenilaianController::class, 'show'])->name('penilaian.show');
        Route::get('/{penilaian}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::get('/penilaian/export-perpegawai', [PenilaianController::class, 'exportExcel'])->name('penilaian.export.perpegawai');
        Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export.excel');
    });

    // Route::post('/penilaian/{section}', [PenilaianController::class, 'store'])
    // ->name('penilaian.store');

    // Route::get('/penilaian/{section}', [PenilaianController::class, 'show'])
    //     ->name('penilaian.section');



});


// routes/web.php

