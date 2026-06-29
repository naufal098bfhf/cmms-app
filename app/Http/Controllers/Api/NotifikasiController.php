<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan semua notifikasi milik user
     */
    public function index($userId)
    {
        $notifikasi = Notifikasi::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notifikasi);
    }

    /**
     * Tandai notifikasi sudah dibaca
     */
    public function read($id)
    {
        $notif = Notifikasi::findOrFail($id);

        $notif->read = true;
        $notif->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibaca'
        ]);
    }

    /**
     * Menghapus notifikasi
     */
    public function destroy($id)
    {
        $notif = Notifikasi::findOrFail($id);

        $notif->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}
