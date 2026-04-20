<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MekanikController;
use Illuminate\Support\Facades\Response;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\TugasTetapController;
use App\Http\Controllers\Admin\TugasDaruratController as AdminTugasDaruratController;
use App\Http\Controllers\Admin\RiwayatTugasController;

// Mekanik Controllers
use App\Http\Controllers\Mekanik\TugasTetapController as MekanikTugasController;
use App\Http\Controllers\Mekanik\TugasDaruratController as MekanikTugasDaruratController;
use App\Http\Controllers\Mekanik\NotifikasiController;

// Maintenance Planning Controllers
use App\Http\Controllers\MaintenancePlanning\DashboardController;
use App\Http\Controllers\MaintenancePlanning\TugasTetapController as MaintenanceTugasTetapController;
use App\Http\Controllers\MaintenancePlanning\TugasDaruratController as MaintenanceTugasDaruratController;
use App\Http\Controllers\MaintenancePlanning\EquipmentController as MaintenanceEquipmentController;
use App\Http\Controllers\MaintenancePlanning\ValidasiTugasController;

/*
|--------------------------------------------------------------------------
| Redirect Root ke Login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
   return view('auth.landing-page');
});

/*
|--------------------------------------------------------------------------
| AUTH (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,maintenance-planning,mekanik'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('kelola-equipment', EquipmentController::class);

    Route::resource('tugas-tetap', TugasTetapController::class)->names([
        'index'   => 'kelola-tugas.tugas-tetap.index',
        'create'  => 'kelola-tugas.tugas-tetap.create',
        'store'   => 'kelola-tugas.tugas-tetap.store',
        'edit'    => 'kelola-tugas.tugas-tetap.edit',
        'update'  => 'kelola-tugas.tugas-tetap.update',
        'destroy' => 'kelola-tugas.tugas-tetap.destroy',
    ]);

    Route::resource('kelola-tugas/tugas-darurat', AdminTugasDaruratController::class)->names([
        'index'   => 'kelola-tugas.tugas-darurat.index',
        'create'  => 'kelola-tugas.tugas-darurat.create',
        'store'   => 'kelola-tugas.tugas-darurat.store',
        'edit'    => 'kelola-tugas.tugas-darurat.edit',
        'update'  => 'kelola-tugas.tugas-darurat.update',
        'destroy' => 'kelola-tugas.tugas-darurat.destroy',
    ]);

    // Riwayat Tugas (Admin bisa lihat semua)
    Route::get('/riwayat-tugas', [RiwayatTugasController::class, 'index'])->name('riwayat-tugas.index');
    Route::get('/riwayat-tugas/{id}', [RiwayatTugasController::class, 'show'])->name('riwayat-tugas.show');

    // Riwayat Tugas Mekanik (akses admin)
    Route::get('/riwayat-tugas/mekanik', [RiwayatTugasController::class, 'index'])->name('riwayat-tugas.mekanik.index');
    Route::get('/riwayat-tugas/mekanik/{id}', [RiwayatTugasController::class, 'show'])->name('riwayat-tugas.mekanik.show');
});

/*
|--------------------------------------------------------------------------
| MEKANIK ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('mekanik')->name('mekanik.')->middleware(['auth', 'role:mekanik'])->group(function () {

    Route::get('/dashboard', [MekanikController::class, 'dashboard'])->name('dashboard');

    // Kelola Tugas Tetap
    Route::prefix('kelola-tugas/tetap')->group(function () {
        Route::get('/index', [MekanikTugasController::class, 'index'])->name('tugas-tetap.index');
        Route::get('/{id}', [MekanikTugasController::class, 'show'])->name('tugas-tetap.show');
        Route::put('/{id}/status', [MekanikTugasController::class, 'updateStatus'])->name('tugas-tetap.update-status');
        Route::post('/{id}/upload', [MekanikTugasController::class, 'uploadBuktiFoto'])->name('tugas-tetap.upload');
    });

    // Kelola Tugas Darurat
    Route::prefix('kelola-tugas/darurat')->group(function () {
        Route::get('/index', [MekanikTugasDaruratController::class, 'index'])->name('tugas-darurat.index');
        Route::get('/{id}', [MekanikTugasDaruratController::class, 'show'])->name('tugas-darurat.show');
        Route::put('/{id}/status', [MekanikTugasDaruratController::class, 'updateStatus'])->name('tugas-darurat.update-status');
        Route::post('/{id}/upload', [MekanikTugasDaruratController::class, 'uploadBuktiFoto'])->name('tugas-darurat.upload');
    });

    // Notifikasi
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');

    // Riwayat Tugas diarahkan ke admin controller
    Route::get('/riwayat-tugas', [\App\Http\Controllers\Admin\RiwayatTugasController::class, 'index'])
         ->name('riwayat-tugas.index');
    Route::get('/riwayat-tugas/{id}', [\App\Http\Controllers\Admin\RiwayatTugasController::class, 'show'])
         ->name('riwayat-tugas.show');
});

Route::post('/mekanik/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])
    ->name('mekanik.notifikasi.markAllRead');

    Route::prefix('mekanik/kelola-tugas/tetap')->name('mekanik.kelola-tugas.tetap.')->group(function () {
    Route::get('{tugas}/show', [App\Http\Controllers\Mekanik\TugasTetapController::class, 'show'])->name('show');
});


/*
|--------------------------------------------------------------------------
| MAINTENANCE PLANNING ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('maintenance-planning')
    ->name('maintenance-planning.')
    ->middleware(['auth', 'maintenance-planning'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Equipment
        Route::prefix('kelola-equipment')->name('kelola-equipment.')->group(function () {
            Route::get('/', [MaintenanceEquipmentController::class, 'index'])->name('index');
            Route::get('/create', [MaintenanceEquipmentController::class, 'create'])->name('create');
            Route::post('/store', [MaintenanceEquipmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [MaintenanceEquipmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MaintenanceEquipmentController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaintenanceEquipmentController::class, 'destroy'])->name('destroy');
        });

        // Kelola Tugas Tetap
        Route::prefix('kelola-tugas/tugas-tetap')->name('kelola-tugas.tugas-tetap.')->group(function () {
            Route::get('/', [MaintenanceTugasTetapController::class, 'index'])->name('index');
            Route::get('/create', [MaintenanceTugasTetapController::class, 'create'])->name('create');
            Route::post('/store', [MaintenanceTugasTetapController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [MaintenanceTugasTetapController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MaintenanceTugasTetapController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaintenanceTugasTetapController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [MaintenanceTugasTetapController::class, 'show'])->name('show');
        });

        // Kelola Tugas Darurat
        Route::prefix('kelola-tugas/tugas-darurat')->name('kelola-tugas.tugas-darurat.')->group(function () {
            Route::get('/', [MaintenanceTugasDaruratController::class, 'index'])->name('index');
            Route::get('/create', [MaintenanceTugasDaruratController::class, 'create'])->name('create');
            Route::post('/store', [MaintenanceTugasDaruratController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [MaintenanceTugasDaruratController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MaintenanceTugasDaruratController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaintenanceTugasDaruratController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [MaintenanceTugasDaruratController::class, 'show'])->name('show');
        });

        // Riwayat Tugas Maintenance Planning
        Route::prefix('riwayat-tugas')->name('riwayat-tugas.')->group(function () {
            Route::get('/', [RiwayatTugasController::class, 'index'])->name('index');
            Route::get('/{id}', [RiwayatTugasController::class, 'show'])->name('show');
        });

        // Validasi Tugas
        Route::prefix('validasi-tugas')->name('validasi-tugas.')->group(function () {
            Route::get('/', [ValidasiTugasController::class, 'index'])->name('index');
            Route::get('/tetap/{id}', [ValidasiTugasController::class, 'showTetap'])->name('tetap');
            Route::put('/tetap/{id}', [ValidasiTugasController::class, 'validasi'])->name('tetap.update');
            Route::get('/darurat/{id}', [ValidasiTugasController::class, 'showDarurat'])->name('darurat');
            Route::put('/darurat/{id}', [ValidasiTugasController::class, 'validasiDarurat'])->name('darurat.update');
        });

        // Show Tugas Tetap MP (alternate route)
        Route::get('/kelola-tugas/tetap/{id}', [MaintenanceTugasTetapController::class, 'show'])
            ->name('kelola-tugas.tetap.show');
    });


/*
|--------------------------------------------------------------------------
| Storage Foto
|--------------------------------------------------------------------------
*/
Route::get('/storage/photos/{filename}', function ($filename) {
    $path = storage_path('app/public/photos/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where('filename', '.*');
