<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\TugasDarurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\TugasBaruNotification;

class KirimTugasSekarangJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tugas_id;
    public $jenis;

    public function __construct($tugas, $jenis)
    {
        $this->tugas_id = $tugas->id;
        $this->jenis = $jenis;
    }

    public function handle()
    {
        $tugas = TugasDarurat::find($this->tugas_id);
        if (!$tugas) return;

        $mekanik = User::find($tugas->mekanik_id);
        if ($mekanik) {
            $mekanik->notify(new TugasBaruNotification($tugas, $this->jenis));
        }

        $tugas->update(['status' => 'dikirim']);
    }
}
