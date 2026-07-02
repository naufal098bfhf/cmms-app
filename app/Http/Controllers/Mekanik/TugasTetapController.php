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

    // WAJIB TAMBAHKAN INI
    $this->generateTugasHariIni();

    $tugasTetap = TugasTetap::where('mekanik_id', Auth::id())
        ->where('is_template', 0)
        ->whereDate('tanggal_mulai', $today)
        ->latest()
        ->get();

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
    ->where('is_template', false)
    ->where('id', $id)
    ->firstOrFail();

        return view('mekanik.kelola-tugas.tetap.show', compact('tugas'));
    }

    // Update status tugas tetap
    public function updateStatus(Request $request, $id)
    {
       $tugas = TugasTetap::where('id', $id)
    ->where('mekanik_id', Auth::id())
    ->where('is_template', false)
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
    ->where('is_template', false)
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
    $tugas = TugasTetap::where('id', $id)
    ->where('mekanik_id', Auth::id())
    ->where('is_template', false)
    ->firstOrFail();

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

            'pesan'=>'Tugas tetap baru diberikan kepada Anda',

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
    $this->generateTugasHariIni();
    try {

      $tugas = TugasTetap::where('mekanik_id', Auth::id())
    ->where('is_template', false)
    ->whereDate('tanggal_mulai', Carbon::today())
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
    $tugas = TugasTetap::where('id', $id)
    ->where('mekanik_id', Auth::id())
    ->where('is_template', false)
    ->firstOrFail();

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
private function generateTugasHariIni()
{

    $today = Carbon::today();

    $hariMap = [
        'Monday' => 'senin',
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu',
        'Sunday' => 'minggu',
    ];

    $hariIni = $hariMap[$today->format('l')];

   $templates = TugasTetap::where('is_template',1)
    ->where('mekanik_id',Auth::id())
    ->get();

        logger()->info('JUMLAH TEMPLATE',[
    'jumlah'=>$templates->count()
]);

    foreach($templates as $tugas){

    logger()->info('TEMPLATE',[
    'id'=>$tugas->id,
    'jenis'=>$tugas->jenis_tugas,
    'tanggal'=>$tugas->tanggal_tahunan,
]);

        $kirim=false;

       if(
    $tugas->jenis_tugas=="mingguan" &&
    strtolower(trim($tugas->hari_mingguan))
    ==
    strtolower(trim($hariIni))
)
{
    $kirim=true;
}

       if(
    $tugas->jenis_tugas=="bulanan" &&
    (int)$tugas->tanggal_bulanan==(int)$today->day
)
{
    $kirim=true;
}

      if (
    $tugas->jenis_tugas == "tahunan" &&
    !empty($tugas->tanggal_tahunan)
) {

    $tanggal = Carbon::parse($tugas->tanggal_tahunan);

    if (
        $tanggal->month == $today->month &&
        $tanggal->day == $today->day
    ) {
        $kirim = true;
    }
        }

        if(!$kirim){
            continue;
        }

       $sudahAda = TugasTetap::where('is_template', 0)
    ->where('mekanik_id', $tugas->mekanik_id)
    ->where('equipment_id', $tugas->equipment_id)
    ->where('jenis_tugas', $tugas->jenis_tugas)
    ->whereDate('tanggal_mulai', $today)
    ->exists();


        if($sudahAda){

    logger()->info('SUDAH ADA TUGAS',[
        'mekanik'=>$tugas->mekanik_id,
        'equipment'=>$tugas->equipment_id
    ]);

   if ($sudahAda) {

    logger()->info('SUDAH ADA TUGAS',[
        'mekanik'=>$tugas->mekanik_id,
        'equipment'=>$tugas->equipment_id
    ]);
}

    continue;
}

        $tugasBaru = TugasTetap::create([

            'pemberi_tugas'=>$tugas->pemberi_tugas,
            'mekanik_id'=>$tugas->mekanik_id,
            'nama_mekanik'=>$tugas->nama_mekanik,

            'equipment_id'=>$tugas->equipment_id,
            'equipment'=>$tugas->equipment,
            'tag_number'=>$tugas->tag_number,

            'jenis_tugas'=>$tugas->jenis_tugas,

            'hari_mingguan'=>$tugas->hari_mingguan,
            'tanggal_bulanan'=>$tugas->tanggal_bulanan,
            'tanggal_tahunan'=>$tugas->tanggal_tahunan,

            'tanggal_mulai'=>$today,

            'eq_class'=>$tugas->eq_class,
            'bom'=>$tugas->bom,
            'task_list'=>$tugas->task_list,
            'lokasi'=>$tugas->lokasi,

            'status'=>'pending',
            'validasi_mp'=>false,
            'is_template'=>0,
        ]);
        logger()->info('BERHASIL MEMBUAT TUGAS',[
    'id'=>$tugasBaru->id,
    'mekanik'=>$tugasBaru->mekanik_id,
]);
Notifikasi::create([

    'user_id'=>$tugasBaru->mekanik_id,

    'pesan'=>'Tugas tetap baru diberikan kepada Anda',

    'link' => '/mekanik/kelola-tugas/tetap/'.$tugasBaru->id,

    'tugas_id'=>$tugasBaru->id,

    'read'=>false,

]);
    }
}
}
