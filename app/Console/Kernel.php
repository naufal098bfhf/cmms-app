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

            $tugasList = TugasTetap::where('status', 'pending')
                ->whereBetween('tanggal_mulai', [$startOfDay, $endOfDay])
                ->get();

            foreach ($tugasList as $tugas) {

                $kirim = false;

                // Cek jadwal
                if ($tugas->jenis_tugas === 'mingguan' && $tugas->hari_mingguan === $hariIni) {
                    $kirim = true;
                }

                if ($tugas->jenis_tugas === 'bulanan' && (int) $tugas->tanggal_bulanan === $today->day) {
                    $kirim = true;
                }

                if ($tugas->jenis_tugas === 'tahunan' && Carbon::parse($tugas->tanggal_tahunan)->isSameDay($today)) {
                    $kirim = true;
                }

                if ($kirim) {
                    // Simpan notifikasi + data tugas
                    Notifikasi::create([
                        'user_id' => $tugas->mekanik_id,
                        'pesan'   => "Release Order: {$tugas->task_list}",
                        'link'    => '/mekanik/tugas-tetap/' . $tugas->id,
                        'read'    => false,
                        'data'    => json_encode([
                            'task_list' => $tugas->task_list,
                            'tgl_mulai' => $tugas->tanggal_mulai,
                            'tgl_selesai'=> $tugas->tanggal_selesai,
                            'jenis_tugas'=> $tugas->jenis_tugas,
                            'equipment' => $tugas->equipment ?? null,
                            'tag_number'=> $tugas->tag_number ?? null,
                            'catatan'   => $tugas->catatan ?? null,
                        ])
                    ]);

                    // Update status menjadi release order
                    $tugas->update(['status' => 'release order']);
                }
            }

        })->dailyAt('07:00'); // dijalankan setiap hari jam 07:00


        /* =========================
         | TUGAS DARURAT TERJADWAL
         ========================= */
        $schedule->call(function () {

            $today = Carbon::today();
            $startOfDay = $today->copy()->startOfDay();
            $endOfDay   = $today->copy()->endOfDay();

            // Ambil tugas darurat yang statusnya terjadwal dan tanggal mulai = hari ini
            $list = TugasDarurat::where('status', 'terjadwal')
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

                // Update status menjadi release order
                $t->update(['status' => 'release order']);
            }

        })->everyMinute(); // cek setiap menit agar tepat tanggal mulai

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
