<?php
namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tugas tetap & darurat yang menunggu validasi
        $tugasTetap = TugasTetap::where('status', 'selesai')
                        ->where('validasi_mp', false)
                        ->get();

        $tugasDarurat = TugasDarurat::where('status', 'selesai')
                        ->where('validasi_mp', false)
                        ->get();

        // Gabungkan jadi satu collection
        $notifikasi = $tugasTetap->concat($tugasDarurat);

        return view('maintenance-planning.dashboard', compact('notifikasi'));
    }
}
