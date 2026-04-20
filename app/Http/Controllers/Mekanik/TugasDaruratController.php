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
        $tugas = TugasDarurat::where('mekanik_id', Auth::user()->id)
            ->where('tgl_mulai', '<', Carbon::today())
            ->latest()
            ->get();

        // Warning otomatis jika deadline terlewati
        foreach ($tugas as $item) {
            if ($item->status === 'selesai') continue;
            if (!$item->batas_waktu) continue;

            $deadline = Carbon::parse($item->batas_waktu);
            $today = Carbon::today();

            if ($today->gt($deadline)) {
                $daysLate = $deadline->diffInDays($today);

                $sudahAda = Notifikasi::where('user_id', Auth::id())
                    ->whereDate('created_at', Carbon::today())
                    ->where('pesan', 'like', "%Tugas Darurat ID {$item->id}%")
                    ->exists();

                if (!$sudahAda) {
                    Notifikasi::create([
                        'user_id'  => Auth::id(),
                        'pesan'    => "⚠️ Warning: Tugas Darurat ID {$item->id} terlambat {$daysLate} hari dari batas waktu!",
                        'link'     => route('mekanik.kelola-tugas.tugas-darurat.show', $item->id),
                        'read'     => false,
                        'tugas_id' => $item->id,
                    ]);
                }
            }
        }

        return view('Mekanik.kelola-tugas.tugas-darurat.index', compact('tugas'));
    }

    public function show($id)
    {
        $tugas = TugasDarurat::where('mekanik_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('Mekanik.kelola-tugas.tugas-darurat.show', compact('tugas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $tugas = TugasDarurat::where('id', $id)
            ->where('mekanik_id', Auth::id())
            ->firstOrFail();

        // Mapping status valid sesuai DB
        $validNext = [
            'pending'    => ['dikerjakan'],
            'dikerjakan' => ['selesai'],
            'selesai'    => [],
        ];

        $newStatus = $request->status;

        // Jika user pilih "release_order" dari UI, simpan sebagai "pending" di DB
        if ($newStatus === 'release_order') {
            $newStatus = 'pending';
        }

        if (!in_array($newStatus, $validNext[$tugas->status])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $tugas->status = $newStatus;

        if ($newStatus === 'selesai') {
            $tugas->validasi_mp = false;
            $tugas->save();

            // Hapus notifikasi lama
            Notifikasi::where('user_id', Auth::id())
                ->where('tugas_id', $tugas->id)
                ->delete();

            // Kirim notifikasi ke Maintenance Planning
            $mpUsers = \App\Models\User::where('role', 'maintenance-planning')->get();
            foreach ($mpUsers as $user) {
                Notifikasi::create([
                    'user_id' => $user->id,
                    'pesan' => "Tugas ID {$tugas->id} dari {$tugas->nama_mekanik} menunggu validasi.",
                    'link' => route('maintenance-planning.kelola-tugas.tugas-darurat.show', $tugas->id),
                    'read' => false,
                    'tugas_id' => $tugas->id,
                ]);
            }

            return redirect()->back()->with('success',  'Status berhasil diperbarui. Tunggu di Validasi Oleh Maintenance Planning.');
        }

        $tugas->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function uploadBuktiFoto(Request $request, $id)
    {
        $tugas = TugasDarurat::where('mekanik_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'bukti_foto' => 'required|image|max:5120',
        ]);

        if ($tugas->bukti_foto) {
            Storage::disk('public')->delete($tugas->bukti_foto);
        }

        $path = $request->file('bukti_foto')->store('tugas-darurat', 'public');
        $tugas->bukti_foto = $path;
        $tugas->save();

        return redirect()->back()->with('success', 'Bukti foto berhasil diupload.');
    }
}
