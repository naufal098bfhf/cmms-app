<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())->update(['read' => true]);
        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}
