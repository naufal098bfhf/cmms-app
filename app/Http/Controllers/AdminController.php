<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\TugasDarurat;
use App\Models\TugasTetap;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahEquipment = Equipment::count();
        $totalTugasDarurat = TugasDarurat::count();
        $totalTugasTetap = TugasTetap::count();

        return view('admin.dashboard', compact(
            'jumlahEquipment',
            'totalTugasDarurat',
            'totalTugasTetap'
        ));
    }
}
