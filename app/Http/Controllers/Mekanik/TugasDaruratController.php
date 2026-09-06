<?php

namespace App\Http\Controllers\Mekanik;

use App\Http\Controllers\Controller;
use App\Models\TugasDarurat;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TugasDaruratController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $tugas = TugasDarurat::where('mekanik_id', Auth::id())
            ->whereDate('tgl_mulai', '=', $today)
            ->latest()
            ->get();

        // ==========================================
        // NOTIFIKASI TUGAS DARURAT HARI INI
        // ==========================================

        foreach ($tugas as $item) {

            // Notifikasi hanya dibuat jika tanggal mulai adalah hari ini
            if (!Carbon::parse($item->tgl_mulai)->isToday()) {
                continue;
            }

            // PERBAIKAN:
            // Tidak menggunakan kolom "type"
            $sudahNotif = Notifikasi::where('user_id', Auth::id())
                ->where('tugas_id', $item->id)
                ->exists();

            if (!$sudahNotif) {

                Notifikasi::create([
                    'user_id'  => Auth::id(),
                    'tugas_id' => $item->id,
                    'pesan'    => "📋 Tugas Darurat {$item->equipment} aktif hari ini.",
                    'link'     => route(
                        'mekanik.tugas-darurat.show',
                        $item->id
                    ),
                    'read'     => false,
                ]);
            }
        }

        // ==========================================
        // WARNING OTOMATIS JIKA DEADLINE TERLEWATI
        // ==========================================

        foreach ($tugas as $item) {

            // Tugas selesai tidak perlu warning
            if ($item->status === 'selesai') {
                continue;
            }

            // Jika tidak memiliki batas waktu
            if (!$item->batas_waktu) {
                continue;
            }

            $deadline = Carbon::parse($item->batas_waktu);
            $today = Carbon::today();

            // Jika sudah melewati deadline
            if ($today->gt($deadline)) {

                $daysLate = $deadline->diffInDays($today);

                $sudahAda = Notifikasi::where(
                    'user_id',
                    Auth::id()
                )
                    ->whereDate(
                        'created_at',
                        Carbon::today()
                    )
                    ->where(
                        'tugas_id',
                        $item->id
                    )
                    ->exists();

                if (!$sudahAda) {

                    Notifikasi::create([
                        'user_id'  => Auth::id(),
                        'pesan'    => "⚠️ Warning: Tugas Darurat ID {$item->id} terlambat {$daysLate} hari dari batas waktu!",
                        'link'     => route(
                            'mekanik.tugas-darurat.show',
                            $item->id
                        ),
                        'read'     => false,
                        'tugas_id' => $item->id,
                    ]);
                }
            }
        }

        return view(
            'Mekanik.kelola-tugas.tugas-darurat.index',
            compact('tugas')
        );
    }

    // ==========================================
    // DETAIL TUGAS DARURAT
    // ==========================================

    public function show($id)
    {
        $tugas = TugasDarurat::where(
            'mekanik_id',
            Auth::user()->id
        )
            ->where('id', $id)
            ->firstOrFail();

        return view(
            'Mekanik.kelola-tugas.tugas-darurat.show',
            compact('tugas')
        );
    }

    // ==========================================
    // UPDATE STATUS
    // ==========================================

    public function updateStatus(Request $request, $id)
    {
        $tugas = TugasDarurat::where('id', $id)
            ->where('mekanik_id', Auth::id())
            ->firstOrFail();

        $validNext = [
            'pending'    => ['dikerjakan'],
            'dikerjakan' => ['selesai'],
            'selesai'    => [],
        ];

        $newStatus = $request->status;

        // release_order disimpan sebagai pending
        if ($newStatus === 'release_order') {
            $newStatus = 'pending';
        }

        if (
            !isset($validNext[$tugas->status]) ||
            !in_array(
                $newStatus,
                $validNext[$tugas->status]
            )
        ) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Status tidak valid.'
                );
        }

        $tugas->status = $newStatus;

        // ==========================================
        // JIKA SELESAI
        // ==========================================

        if ($newStatus === 'selesai') {

            $tugas->validasi_mp = false;
            $tugas->save();

            // Hapus notifikasi lama
            Notifikasi::where(
                'user_id',
                Auth::id()
            )
                ->where(
                    'tugas_id',
                    $tugas->id
                )
                ->delete();

            // Kirim notifikasi ke Maintenance Planning
            $mpUsers = \App\Models\User::where(
                'role',
                'maintenance-planning'
            )->get();

            foreach ($mpUsers as $user) {

                Notifikasi::create([
                    'user_id' => $user->id,
                    'pesan' => "Tugas ID {$tugas->id} dari {$tugas->nama_mekanik} menunggu validasi.",
                    'link' => route(
                        'maintenance-planning.kelola-tugas.tugas-darurat.show',
                        $tugas->id
                    ),
                    'read' => false,
                    'tugas_id' => $tugas->id,
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Status berhasil diperbarui. Tunggu di Validasi Oleh Maintenance Planning.'
                );
        }

        $tugas->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Status berhasil diperbarui.'
            );
    }

    // ==========================================
    // UPLOAD BUKTI FOTO WEB
    // ==========================================

    public function uploadBuktiFoto(
        Request $request,
        $id
    ) {
        $tugas = TugasDarurat::where(
            'mekanik_id',
            Auth::user()->id
        )
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'bukti_foto' => 'required|image|max:5120',
        ]);

        // Hapus foto lama
        if ($tugas->bukti_foto) {
            Storage::disk('public')->delete(
                $tugas->bukti_foto
            );
        }

        $path = $request
            ->file('bukti_foto')
            ->store(
                'tugas-darurat',
                'public'
            );

        $tugas->bukti_foto = $path;
        $tugas->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Bukti foto berhasil diupload.'
            );
    }

    // ==========================================
    // API INDEX
    // ==========================================

    public function apiIndex(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {

                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            $tugas = TugasDarurat::where(
                'mekanik_id',
                $user->id
            )
                ->latest()
                ->get();

            return response()->json(
                $tugas
            );

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    // ==========================================
    // API UPDATE STATUS
    // ==========================================

    public function apiUpdateStatus(
        Request $request,
        $id
    ) {
        $tugas = TugasDarurat::where(
            'id',
            $id
        )
            ->where(
                'mekanik_id',
                auth()->id()
            )
            ->firstOrFail();

        $validNext = [

            'pending' => [
                'dikerjakan'
            ],

            'dikerjakan' => [
                'selesai'
            ],

            'selesai' => [],
        ];

        $newStatus = $request->status;

        if ($newStatus === 'release_order') {
            $newStatus = 'pending';
        }

        if (
            !isset($validNext[$tugas->status]) ||
            !in_array(
                $newStatus,
                $validNext[$tugas->status]
            )
        ) {

            return response()->json([

                'success' => false,

                'message' => 'Status tidak valid'

            ], 400);
        }

        // ==========================================
        // UPDATE STATUS
        // ==========================================

        $tugas->status = $newStatus;

        // ==========================================
        // JIKA SELESAI
        // ==========================================

        if ($newStatus === 'selesai') {

            $tugas->validasi_mp = false;

            $tugas->save();

            // ==========================================
            // HAPUS NOTIFIKASI LAMA
            // ==========================================

            Notifikasi::where(
                'tugas_id',
                $tugas->id
            )->delete();

            // ==========================================
            // KIRIM NOTIFIKASI KE MP
            // ==========================================

            $mpUsers = \App\Models\User::where(
                'role',
                'maintenance-planning'
            )->get();

            foreach ($mpUsers as $user) {

                Notifikasi::create([

                    'user_id' => $user->id,

                    'pesan' =>
                        "Tugas ID {$tugas->id} dari {$tugas->nama_mekanik} menunggu validasi.",

                    'link' =>
                        '/maintenance-planning/kelola-tugas/tugas-darurat/' .
                        $tugas->id,

                    'read' => false,

                    'tugas_id' => $tugas->id,
                ]);
            }

            return response()->json([

                'success' => true,

                'message' =>
                    'Status selesai. Menunggu validasi Maintenance Planning.',

                'status_label' =>
                    'Menunggu Validasi MP'

            ]);
        }

        // ==========================================
        // SAVE NORMAL
        // ==========================================

        $tugas->save();

        return response()->json([

            'success' => true,

            'message' =>
                'Status berhasil diupdate'

        ]);
    }

    // ==========================================
    // API UPLOAD FOTO
    // ==========================================

    public function apiUploadFoto(
        Request $request,
        $id
    ) {
        $user = $request->user();

        $tugas = TugasDarurat::where(
            'id',
            $id
        )
            ->where(
                'mekanik_id',
                $user->id
            )
            ->firstOrFail();

        if ($request->hasFile('bukti_foto')) {

            $path = $request
                ->file('bukti_foto')
                ->store(
                    'tugas-darurat',
                    'public'
                );

            $tugas->bukti_foto = $path;

            $tugas->save();
        }

        return response()->json([

            'success' => true,

            'message' =>
                'Foto berhasil diupload'

        ]);
    }
}
