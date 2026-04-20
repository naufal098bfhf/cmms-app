<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;

class MekanikController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:mekanik']);
    }

    /**
     * Tampilkan dashboard mekanik.
     */
    public function dashboard()
    {
        $mekanikId = Auth::user()->id;

        // Ambil semua tugas milik mekanik login
        $tugasDarurat = TugasDarurat::where('mekanik_id', $mekanikId)->latest()->get();
        $tugasTetap   = TugasTetap::where('mekanik_id', $mekanikId)->latest()->get();

        // Statistik untuk ringkasan di atas dashboard
        $jumlahEquipment   = Equipment::count();
        $jumlahTugasHariIni = TugasDarurat::whereDate('tgl_mulai', now()->toDateString())
                                ->where('mekanik_id', $mekanikId)
                                ->count();

        $jumlahPending     = TugasDarurat::where('mekanik_id', $mekanikId)
                                ->where('status', 'pending')
                                ->count();

        $jumlahSelesai     = TugasDarurat::where('mekanik_id', $mekanikId)
                                ->where('status', 'selesai')
                                ->count();

        return view('Mekanik.dashboard', compact(
            'tugasDarurat',
            'tugasTetap',
            'jumlahEquipment',
            'jumlahTugasHariIni',
            'jumlahPending',
            'jumlahSelesai'
        ));
    }
}
