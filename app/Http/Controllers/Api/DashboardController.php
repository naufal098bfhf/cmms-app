<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahEquipment = Equipment::count();
        $totalTugasTetap = TugasTetap::count();
        $totalTugasDarurat = TugasDarurat::count();

        $tugasSelesai =
            TugasTetap::where('status', 'selesai')->count() +
            TugasDarurat::where('status', 'selesai')->count();

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
    }
}
