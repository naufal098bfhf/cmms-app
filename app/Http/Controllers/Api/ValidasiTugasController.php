<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;

class ValidasiTugasController extends Controller
{
    public function index()
    {
        $tetap = TugasTetap::where(
            'status',
            'selesai'
        )
        ->where(
            'validasi_mp',
            false
        )
        ->get();

        $darurat = TugasDarurat::where(
            'status',
            'selesai'
        )
        ->where(
            'validasi_mp',
            false
        )
        ->get();

        return response()->json([
            'tetap' => $tetap,
            'darurat' => $darurat,
        ]);
    }

 public function validasiTetapApi($id)
{
    $tugas = TugasTetap::findOrFail($id);

    $tugas->validasi_mp = true;

    $tugas->save();

    return response()->json([
        'success' => true,
        'message' => 'Berhasil divalidasi'
    ]);
}
    public function validasiDaruratApi($id)
{
    $tugas = TugasDarurat::findOrFail($id);

    $tugas->validasi_mp = true;

    $tugas->save();

    return response()->json([
        'success' => true,
        'message' => 'Berhasil divalidasi'
    ]);
}
}
