<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;

class ValidasiTugasController extends Controller
{
    public function index()
    {
        $tugasTetap = TugasTetap::where('status', 'selesai')
                                ->where('validasi_mp', false)
                                ->get();

        $tugasDarurat = TugasDarurat::where('status', 'selesai')
                                    ->where('validasi_mp', false)
                                    ->get();

        return view('maintenance-planning.validasi-tugas', compact('tugasTetap', 'tugasDarurat'));
    }
}
