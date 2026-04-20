<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;

class ValidasiTugasController extends Controller
{
    // Menampilkan daftar semua tugas selesai yang belum divalidasi
    public function index()
    {
        $tugasTetap = TugasTetap::where('status', 'selesai')
                                ->where('validasi_mp', false)
                                ->get();

        $tugasDarurat = TugasDarurat::where('status', 'selesai')
                                    ->where('validasi_mp', false)
                                    ->get();

        // ✅ Arahkan ke halaman daftar tugas darurat sesuai permintaanmu
        return view('maintenance-planning.kelola-tugas.tugas-darurat.index', compact('tugasTetap', 'tugasDarurat'));
    }

    // Halaman detail tugas tetap
    public function showTetap($id)
    {
        $tugas = TugasTetap::with(['mekanik', 'equipmentRelasi'])->findOrFail($id);
        return view('maintenance-planning.validasi-tugas.tetap', compact('tugas'));
    }

    // Halaman detail tugas darurat
    public function showDarurat($id)
    {
        $tugas = TugasDarurat::with(['mekanik', 'equipmentRelasi'])->findOrFail($id);
        return view('maintenance-planning.validasi-tugas.darurat', compact('tugas'));
    }

  // Validasi tugas tetap
public function validasi($id)
{
    $tugas = TugasTetap::findOrFail($id);
    $tugas->validasi_mp = true;

    // ✅ Setelah divalidasi MP, status otomatis jadi 'selesai'
    $tugas->status = 'selesai';

    $tugas->save();

    return redirect()->route('maintenance-planning.kelola-tugas.tugas-tetap.index')
                     ->with('success', 'Tugas tetap berhasil divalidasi.');
}

// Validasi tugas darurat
public function validasiDarurat($id)
{
    $tugas = TugasDarurat::findOrFail($id);
    $tugas->validasi_mp = true;

    // ✅ Setelah divalidasi MP, status otomatis jadi 'selesai'
    $tugas->status = 'selesai';

    $tugas->save();

    return redirect()->route('maintenance-planning.kelola-tugas.tugas-darurat.index')
                     ->with('success', 'Tugas darurat berhasil divalidasi.');
}
}
