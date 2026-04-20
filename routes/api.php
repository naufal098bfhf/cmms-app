<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EquipmentController;

// 🔐 LOGIN
Route::post('/login', [AuthController::class, 'login']);

// 🚀 DASHBOARD (FINAL)
Route::get('/dashboard', function () {

    $jumlahEquipment = Equipment::count();
    $totalTugasTetap = TugasTetap::count();
    $totalTugasDarurat = TugasDarurat::count();

    $tugasSelesai =
        TugasTetap::where('status','selesai')->count() +
        TugasDarurat::where('status','selesai')->count();

    $tugasTetap = TugasTetap::latest()->get();
    $tugasDarurat = TugasDarurat::latest()->get();

    $tugas = [];

    foreach ($tugasTetap as $t) {
        $tugas[] = [
            'jenis' => 'Tugas Tetap',
            'pemberi_tugas' => $t->pemberi_tugas,
            'tgl_mulai' => $t->created_at->format('Y-m-d'),
            'tgl_selesai' => '-',
            'equipment' => $t->equipment,
            'lokasi' => $t->lokasi,
            'status' => $t->status,
        ];
    }

    foreach ($tugasDarurat as $t) {
        $tugas[] = [
            'jenis' => 'Tugas Darurat',
            'pemberi_tugas' => $t->pemberi_tugas,
            'tgl_mulai' => $t->tgl_mulai,
            'tgl_selesai' => $t->tgl_selesai,
            'equipment' => $t->equipment,
            'lokasi' => $t->lokasi,
            'status' => $t->status,
        ];
    }

    return response()->json([
        'jumlah_equipment' => $jumlahEquipment,
        'tugas_tetap' => $totalTugasTetap,
        'tugas_darurat' => $totalTugasDarurat,
        'tugas_selesai' => $tugasSelesai,
        'tugas' => $tugas,
    ]);
});

// 🔥 USERS CRUD
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


// 🔥 EQUIPMENT CRUD
Route::get('/equipment', [EquipmentController::class, 'index']);
Route::post('/equipment', [EquipmentController::class, 'store']);
Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);
