<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;
use App\Models\Notifikasi;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        /* =========================
         | TUGAS TETAP
         ========================= */
        $schedule->call(function () {

            $today = Carbon::today();
            $startOfDay = $today->copy()->startOfDay(); // 00:00:00
            $endOfDay   = $today->copy()->endOfDay();   // 23:59:59

            // Mapping hari ke bahasa Indonesia
            $hariMap = [
                'Monday'    => 'senin',
                'Tuesday'   => 'selasa',
                'Wednesday' => 'rabu',
                'Thursday'  => 'kamis',
                'Friday'    => 'jumat',
                'Saturday'  => 'sabtu',
                'Sunday'    => 'minggu',
            ];
            $hariIni = $hariMap[$today->format('l')];

           $tugasList = TugasTetap::where('is_template', 1)->get();

            foreach ($tugasList as $tugas) {

    if (
        $tugas->last_sent &&
        Carbon::parse($tugas->last_sent)->isToday()
    ) {
        continue;
    }

    $kirim = false;

    // =====================
    // MINGGUAN
    // =====================
   if (
    $tugas->jenis_tugas == 'mingguan' &&
    strtolower(trim($tugas->hari_mingguan)) == strtolower(trim($hariIni))
) {
    $kirim = true;
}

    // =====================
    // BULANAN
    // =====================
 if (
    $tugas->jenis_tugas == 'bulanan' &&
    !empty($tugas->tanggal_bulanan) &&
    (int)$tugas->tanggal_bulanan === (int)$today->day
) {
    $kirim = true;
}

    // =====================
    // TAHUNAN
    // =====================
   if (
    $tugas->jenis_tugas == 'tahunan' &&
    !empty($tugas->tanggal_tahunan) &&
    Carbon::parse($tugas->tanggal_tahunan)->isSameDay($today)
) {
    $kirim = true;
}

    if (!$kirim) {
        continue;
    }
    $sudahAda = TugasTetap::where('is_template', 0)
    ->where('mekanik_id', $tugas->mekanik_id)
    ->where('equipment_id', $tugas->equipment_id)
    ->whereDate('tanggal_mulai', $today)
    ->exists();

if ($sudahAda) {
    continue;
}
logger()->info('MEMBUAT TUGAS', [
    'id' => $tugas->id,
    'jenis' => $tugas->jenis_tugas,
    'mekanik' => $tugas->mekanik_id,
]);
$tugasBaru = TugasTetap::create([

    'pemberi_tugas'   => $tugas->pemberi_tugas,
    'mekanik_id'      => $tugas->mekanik_id,
    'nama_mekanik'    => $tugas->nama_mekanik,

    'equipment_id'    => $tugas->equipment_id,
    'equipment'       => $tugas->equipment,
    'tag_number'      => $tugas->tag_number,

    'jenis_tugas'     => $tugas->jenis_tugas,

    'hari_mingguan'   => $tugas->hari_mingguan,
    'tanggal_bulanan' => $tugas->tanggal_bulanan,
    'tanggal_tahunan' => $tugas->tanggal_tahunan,

    'tanggal_mulai'   => $today,

    'eq_class'        => $tugas->eq_class,
    'bom'             => $tugas->bom,
    'task_list'       => $tugas->task_list,
    'lokasi'          => $tugas->lokasi,

    'status'          => 'pending',

    'validasi_mp'     => false,

    'is_template' => 0,

'last_sent' => null,

]);

Notifikasi::create([

    'user_id' => $tugas->mekanik_id,

    'pesan'   => "Release Order : {$tugas->task_list}",

    'link' => '/mekanik/tugas-tetap/' . $tugasBaru->id,

    'read'    => false,

]);

$tugas->update([
    'last_sent' => Carbon::now()
]);
}   // tutup foreac

})->dailyAt('07:00');


/* =========================
| TUGAS DARURAT TERJADWAL
========================= */

$schedule->call(function () {

            $today = Carbon::today();
            $startOfDay = $today->copy()->startOfDay();
            $endOfDay   = $today->copy()->endOfDay();

            // Ambil tugas darurat yang statusnya terjadwal dan tanggal mulai = hari ini
            $list = TugasDarurat::where('status', 'pending')
    ->whereBetween('tgl_mulai', [$startOfDay, $endOfDay])
    ->get();
            foreach ($list as $t) {

                // Simpan notifikasi + data tugas
                Notifikasi::create([
                    'user_id' => $t->mekanik_id,
                    'pesan'   => "Tugas Darurat: {$t->task_list}",
                    'link'    => '/mekanik/tugas-darurat/' . $t->id,
                    'read'    => false,
                    'data'    => json_encode([
                        'task_list' => $t->task_list,
                        'tgl_mulai' => $t->tgl_mulai,
                        'tgl_selesai'=> $t->tgl_selesai,
                        'equipment' => $t->equipment ?? null,
                        'tag_number'=> $t->tag_number ?? null,
                        'catatan'   => $t->catatan ?? null,
                    ])
                ]);

               // Tandai bahwa notifikasi sudah pernah dikirim
$t->update([
    'notifikasi_terkirim' => true
]);
            }

        })->everyMinute(); // cek setiap menit agar tepat tanggal mulai

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
