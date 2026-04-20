<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;
use App\Models\Notifikasi;
use Carbon\Carbon;

class KirimNotifikasiTugasLengkap extends Command
{
    protected $signature = 'tugas:kirim-lengkap';
    protected $description = 'Kirim notifikasi beserta data tugas untuk Tugas Tetap & Darurat';

    public function handle()
    {
        $today = Carbon::today();

        // ======= TUGAS TETAP =======
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

        $tugasTetapHariIni = TugasTetap::where('status', 'pending')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->where(function ($q) use ($hariIni, $today) {
                $q->where(function ($q1) use ($hariIni) {
                        $q1->where('jenis_tugas', 'mingguan')
                           ->where('hari_mingguan', $hariIni);
                    })
                  ->orWhere(function ($q2) use ($today) {
                        $q2->where('jenis_tugas', 'bulanan')
                           ->where('tanggal_bulanan', $today->day);
                    })
                  ->orWhere(function ($q3) use ($today) {
                        $q3->where('jenis_tugas', 'tahunan')
                           ->whereDate('tanggal_tahunan', $today->toDateString());
                    });
            })
            ->get();

        foreach ($tugasTetapHariIni as $tugas) {
            // Notifikasi + data tugas
            Notifikasi::create([
                'user_id' => $tugas->mekanik_id,
                'pesan'   => 'Release Order: ' . $tugas->task_list,
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

            // Update status tugas menjadi release order
            $tugas->update(['status' => 'release order']);
        }

        // ======= TUGAS DARURAT =======
        $tugasDarurat = TugasDarurat::where('status', 'release order')
            ->whereDate('tgl_mulai', '<=', $today)
            ->get();

        foreach ($tugasDarurat as $tugas) {
            // Notifikasi + data tugas
            Notifikasi::create([
                'user_id' => $tugas->mekanik_id,
                'pesan'   => 'Tugas Darurat: ' . $tugas->task_list,
                'link'    => '/mekanik/tugas-darurat/' . $tugas->id,
                'read'    => false,
                'data'    => json_encode([
                    'task_list' => $tugas->task_list,
                    'tgl_mulai' => $tugas->tgl_mulai,
                    'tgl_selesai'=> $tugas->tgl_selesai,
                    'equipment' => $tugas->equipment ?? null,
                    'tag_number'=> $tugas->tag_number ?? null,
                    'catatan'   => $tugas->catatan ?? null,
                ])
            ]);

            // Update status menjadi "dikerjakan"
            $tugas->update(['status' => 'dikerjakan']);
        }

        $this->info('Notifikasi tugas tetap & darurat berhasil dikirim beserta data tugas.');
    }
}
