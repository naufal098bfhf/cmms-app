<?php

namespace App\Http\Controllers\Mekanik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function markAllRead(Request $request)
    {
        $user = auth()->user();

        // Cek apakah user punya notifikasi belum dibaca
        if ($user->unreadNotifications->isNotEmpty()) {
            $user->unreadNotifications->markAsRead();
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
