<?php

namespace App\Http\Controllers\Mekanik;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TugasTetapController extends Controller
{
    // Halaman daftar tugas tetap milik mekanik login
    public function index()
    {
$today = Carbon::today();

$tugasTetap = TugasTetap::where('mekanik_id', Auth::id())

    // hanya tugas aktif
    ->where(function ($query) use ($today) {

        /*
        |--------------------------------------------------------------------------
        | HARIAN
        |--------------------------------------------------------------------------
        */
        $query->where('jenis_tugas', 'harian');

        /*
        |--------------------------------------------------------------------------
        | MINGGUAN
        |--------------------------------------------------------------------------
        */
        $query->orWhere(function ($q) use ($today) {

            $mappingHari = [
                'monday' => 'senin',
                'tuesday' => 'selasa',
                'wednesday' => 'rabu',
                'thursday' => 'kamis',
                'friday' => 'jumat',
                'saturday' => 'sabtu',
                'sunday' => 'minggu',
            ];

            $hariIndonesia = $mappingHari[strtolower($today->format('l'))];

            $q->where('jenis_tugas', 'mingguan')
              ->whereRaw('LOWER(hari_mingguan) = ?', [$hariIndonesia]);
        });

        /*
        |--------------------------------------------------------------------------
        | BULANAN
        |--------------------------------------------------------------------------
        */
        $query->orWhere(function ($q) use ($today) {

            $q->where('jenis_tugas', 'bulanan')
              ->whereDay('tanggal_bulanan', $today->day);
        });

        /*
        |--------------------------------------------------------------------------
        | TAHUNAN
        |--------------------------------------------------------------------------
        */
        $query->orWhere(function ($q) use ($today) {

            $q->where('jenis_tugas', 'tahunan')
              ->whereMonth('tanggal_tahunan', $today->month)
              ->whereDay('tanggal_tahunan', $today->day);
        });

    })

    ->latest()
    ->get();


/*
|--------------------------------------------------------------------------
| RESET STATUS OTOMATIS AGAR MUNCUL LAGI
|--------------------------------------------------------------------------
*/

foreach ($tugasTetap as $tugas) {

    // jika tugas selesai tetapi sudah masuk jadwal baru
    if ($tugas->status === 'selesai') {

        $lastUpdate = Carbon::parse($tugas->updated_at);

        $reset = false;

        switch ($tugas->jenis_tugas) {

            case 'harian':
                $reset = !$lastUpdate->isToday();
                break;

            case 'mingguan':
                $reset = $lastUpdate->startOfWeek() != $today->copy()->startOfWeek();
                break;

            case 'bulanan':
                $reset = $lastUpdate->month != $today->month
                      || $lastUpdate->year != $today->year;
                break;

            case 'tahunan':
                $reset = $lastUpdate->year != $today->year;
                break;
        }

        if ($reset) {

            $tugas->status = 'pending';
            $tugas->validasi_mp = 0;
            $tugas->save();
        }
        if ($reset) {

    // reset status tugas
    $tugas->status = 'pending';
    $tugas->validasi_mp = 0;
    $tugas->save();

    /*
    |--------------------------------------------------------------------------
    | CEK NOTIFIKASI HARI INI
    |--------------------------------------------------------------------------
    */

    $sudahNotif = Notifikasi::where('user_id', $tugas->mekanik_id)
        ->whereDate('created_at', Carbon::today())
        ->where('pesan', 'like', "%Tugas Tetap ID {$tugas->id}%")
        ->exists();

    /*
    |--------------------------------------------------------------------------
    | KIRIM NOTIFIKASI BARU
    |--------------------------------------------------------------------------
    */

    if (!$sudahNotif) {

        Notifikasi::create([
            'user_id' => $tugas->mekanik_id,

            'pesan' =>
                "📋 Tugas Tetap ID {$tugas->id} kembali aktif hari ini.",

            'link' =>
                route('mekanik.kelola-tugas.tetap.show', $tugas->id),

            'read' => false,
        ]);
    }
}
    }
}

        // ================================
        // 🔥 FITUR WARNING OTOMATIS PER HARI
        // ================================
        foreach ($tugasTetap as $tugas) {

            // Abaikan jika tugas sudah selesai
            if ($tugas->status === 'selesai') {
                continue;
            }

            // Abaikan jika tidak ada batas waktu
            if (!$tugas->batas_waktu) {
                continue;
            }

            $deadline = Carbon::parse($tugas->batas_waktu);
            $today = Carbon::today();

            // Jika sudah melewati deadline
            if ($today->gt($deadline)) {

                // Hitung berapa hari terlambat
                $daysLate = $deadline->diffInDays($today);

                // Cek apakah warning hari ini sudah dikirim
                $sudahAda = Notifikasi::where('user_id', Auth::user()->id)
                    ->whereDate('created_at', Carbon::today())
                    ->where('pesan', 'like', "%Tugas Tetap ID {$tugas->id}%")
                    ->exists();

                if (!$sudahAda) {
                    Notifikasi::create([
                        'user_id'  => Auth::user()->id,
                        'pesan'    => "⚠️ Warning: Tugas Tetap ID {$tugas->id} terlambat {$daysLate} hari dari batas waktu!",
                        'link'     => route('mekanik.kelola-tugas.tetap.show', $tugas->id),
                        'read'     => false,
                    ]);
                }
            }
        }
        // ================================

        return view('Mekanik.kelola-tugas.tetap.index', compact('tugasTetap'));
    }

    // Detail satu tugas tetap
    public function show($id)
    {
        $tugas = TugasTetap::where('mekanik_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('mekanik.kelola-tugas.tetap.show', compact('tugas'));
    }

    // Update status tugas tetap
    public function updateStatus(Request $request, $id)
    {
        $tugas = TugasTetap::where('id', $id)
            ->where('mekanik_id', Auth::id())
            ->firstOrFail();

        $validNext = [
            'pending' => ['dikerjakan'],
            'dikerjakan' => ['selesai'],
            'selesai' => [],
        ];

        $newStatus = $request->status;

        if (!isset($validNext[$tugas->status]) || !in_array($newStatus, $validNext[$tugas->status])) {
            return redirect()->back()->with('error', 'Status tidak valid. Urutan harus: Pending → Dikerjakan → Selesai.');
        }

        $tugas->status = $newStatus;

        if ($newStatus === 'selesai') {
            $tugas->validasi_mp = 0;
            $tugas->save();

            $mekanikUser = User::where('id', $tugas->mekanik_id)
                ->where('role', 'mekanik')
                ->first();

            if ($mekanikUser) {
                Notifikasi::create([
                    'user_id' => $mekanikUser->id,
                    'pesan'   => "Tugas Tetap ID {$tugas->id} telah diselesaikan dan menunggu validasi.",
                    'link'    => route('mekanik.kelola-tugas.tetap.show', $tugas->id),
                    'read'    => false,
                ]);
            }

            return redirect()->back()->with('success', 'Status berhasil diperbarui. Tunggu di Validasi Oleh Maintenance Planning.');
        }

        $tugas->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    // Upload / ganti bukti foto tugas tetap
    public function uploadBuktiFoto(Request $request, $id)
    {
        $tugas = TugasTetap::where('mekanik_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'bukti_foto' => 'required|image|max:5120',
        ]);

        if ($tugas->bukti_foto) {
            Storage::disk('public')->delete($tugas->bukti_foto);
        }

        $path = $request->file('bukti_foto')->store('tugas-tetap', 'public');
        $tugas->bukti_foto = $path;
        $tugas->save();

        return redirect()->back()->with('success', 'Bukti foto berhasil diupload.');
    }

    public function apiUpdateStatus(
    Request $request,
    $id
)
{
    $tugas = TugasTetap::findOrFail($id);

    $tugas->status =
        $request->status;

    if (
    $request->status ==
    'selesai'
)
{
    $tugas->validasi_mp = 0;

    $maintenance =
        User::where(
            'role',
            'maintenance'
        )->get();

    foreach ($maintenance as $m) {

        Notifikasi::create([
            'user_id' => $m->id,

            'pesan' =>
                "Tugas Tetap ID {$tugas->id} menunggu validasi MP",

            'link' =>
                '/maintenance/tugas-tetap',

            'read' => false,
        ]);
    }
}

    $tugas->save();

    return response()->json([
    'success' => true,
    'id' => $tugas->id,
    'status' => $tugas->status,
    'validasi_mp' => $tugas->validasi_mp,
]);
}
public function apiIndex()
{
    try {

        $tugas = TugasTetap::where(
            'mekanik_id',
            Auth::id()
        )
        ->latest()
        ->get();

        return response()->json(
            $tugas,
            200
        );

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}
public function apiUploadFoto(
    Request $request,
    $id
)
{
    $tugas = TugasTetap::findOrFail($id);

    $request->validate([
        'foto' => 'required|image|max:5120'
    ]);

    $path = $request
        ->file('foto')
        ->store(
            'tugas-tetap',
            'public'
        );

    $tugas->bukti_foto = $path;
    $tugas->save();

    return response()->json([
        'success' => true,
        'foto' => $path
    ]);
}
}
