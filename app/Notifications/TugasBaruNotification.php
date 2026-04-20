<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
{
    protected $tugas;
    protected $jenisTugas;

    public function __construct($tugas, $jenisTugas = 'tugas_darurat')
    {
        $this->tugas = $tugas;
        $this->jenisTugas = $jenisTugas;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $judul = $this->jenisTugas === 'tugas_tetap' ? 'Tugas Tetap Baru' : 'Tugas Darurat Baru';
        $pesan = 'Kamu mendapat ' . $this->jenisTugas . ' baru dari ' . ($this->tugas->pemberi_tugas ?? '-');

        return [
            'jenis'       => $this->jenisTugas,
            'judul'       => $judul,
            'pesan'       => $pesan,
            'mekanik'     => $this->tugas->nama_mekanik ?? '',
            'equipment'   => $this->tugas->equipment ?? '',
            'lokasi'      => $this->tugas->lokasi ?? '',
            'tgl_mulai'   => $this->tugas->tgl_mulai ?? null,
            'tgl_selesai' => $this->tugas->tgl_selesai ?? null,
            'status'      => $this->tugas->status ?? 'pending',
            'tugas_id'    => $this->tugas->id,
        ];
    }
}
