<?php

namespace App\Http\Controllers\Mekanik;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\TugasDarurat;
use App\Models\TugasTetap;
use App\Models\Equipment;

class DashboardController extends Controller
{
    public function mekanik()
    {
        // =========================================
        // USER LOGIN
        // =========================================

        $mekanikId = Auth::id();

        // =========================================
        // TANGGAL HARI INI
        // =========================================

        $today = Carbon::today();

        // =========================================
        // JUMLAH EQUIPMENT
        // =========================================

        $jumlahEquipment =
            Equipment::count();

        // =========================================
        // TUGAS HARI INI
        // HANYA TUGAS HARI INI
        // DAN MILIK MEKANIK LOGIN
        // =========================================

        $tugasHariIni =

    TugasTetap::where('mekanik_id', $mekanikId)
        ->where('is_template', false)
        ->whereIn('status', [
            'pending',
            'dikerjakan'
        ])
        ->count()

    +

    TugasDarurat::where('mekanik_id', $mekanikId)
        ->whereIn('status', [
            'pending',
            'dikerjakan'
        ])
        ->count();

        // =========================================
        // TUGAS MENUNGGU
        // HANYA MILIK MEKANIK LOGIN
        // =========================================

       $tugasPending =

    TugasTetap::where('mekanik_id', $mekanikId)
        ->where('is_template', false)
        ->where('status', 'pending')
        ->count()

    +

    TugasDarurat::where('mekanik_id', $mekanikId)
        ->where('status', 'pending')
        ->count();

        // =========================================
        // TUGAS SELESAI
        // HANYA MILIK MEKANIK LOGIN
        // =========================================

        $tugasSelesai =

            TugasTetap::where(
                'mekanik_id',
                $mekanikId
            )
            ->where(
                'status',
                'selesai'
            )
            ->count()

            +

            TugasDarurat::where(
                'mekanik_id',
                $mekanikId
            )
            ->where(
                'status',
                'selesai'
            )
            ->count();

        // =========================================
        // LIST TUGAS TETAP
        // =========================================

        $tugasTetap =
            TugasTetap::where(
                'mekanik_id',
                $mekanikId
            )
            ->latest()
            ->get()
            ->map(function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'jenis_tugas' =>
                        'tetap',

                    'equipment' =>
                        $item->equipment
                        ?? '-',

                    'lokasi' =>
                        $item->lokasi
                        ?? '-',

                    'status' =>
                        $item->status,

                    'tanggal' =>
                        $item->tanggal_mulai,

                    'created_at' =>
                        $item->created_at,
                ];
            });

        // =========================================
        // LIST TUGAS DARURAT
        // =========================================

        $tugasDarurat =
            TugasDarurat::where(
                'mekanik_id',
                $mekanikId
            )
            ->latest()
            ->get()
            ->map(function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'jenis_tugas' =>
                        'darurat',

                    'equipment' =>
                        $item->equipment
                        ?? '-',

                    'lokasi' =>
                        $item->lokasi
                        ?? '-',

                    'status' =>
                        $item->status,

                    'tanggal' =>
                        $item->tgl_mulai,

                    'created_at' =>
                        $item->created_at,
                ];
            });

        // =========================================
        // GABUNGKAN SEMUA TUGAS
        // =========================================

        $tugas = $tugasTetap
            ->concat($tugasDarurat)
            ->sortByDesc('created_at')
            ->values();

        // =========================================
        // RESPONSE
        // =========================================

        return response()->json([

            'success' => true,

            'jumlah_equipment' =>
                $jumlahEquipment,

            'tugas_hari_ini' =>
                $tugasHariIni,

            'tugas_pending' =>
                $tugasPending,

            'tugas_selesai' =>
                $tugasSelesai,

            'tugas' =>
                $tugas,
        ]);
    }
}
