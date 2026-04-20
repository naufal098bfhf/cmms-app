<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TugasTetap;
use App\Models\TugasDarurat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RiwayatTugasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $search    = $request->input('search');

        $queryTetap = TugasTetap::query();
        $queryDarurat = TugasDarurat::query();

        // Filter tanggal
        if ($startDate && $endDate) {

            // TABEL TUGAS TETAP → gunakan tanggal_mulai
            $queryTetap->whereBetween('tanggal_mulai', [$startDate, $endDate]);

            // TABEL TUGAS DARURAT → tetap gunakan tgl_mulai
            $queryDarurat->whereBetween('tgl_mulai', [$startDate, $endDate]);
        }

        // Filter pencarian teks
        if ($search) {
            $queryTetap->where(function($q) use ($search) {
                $q->where('pemberi_tugas', 'like', "%$search%")
                  ->orWhere('nama_mekanik', 'like', "%$search%")
                  ->orWhere('equipment', 'like', "%$search%")
                  ->orWhere('tag_number', 'like', "%$search%");
            });

            $queryDarurat->where(function($q) use ($search) {
                $q->where('pemberi_tugas', 'like', "%$search%")
                  ->orWhere('nama_mekanik', 'like', "%$search%")
                  ->orWhere('equipment', 'like', "%$search%")
                  ->orWhere('tag_number', 'like', "%$search%");
            });
        }

        // Ambil data
        $tugasTetap = $queryTetap->get();
        $tugasDarurat = $queryDarurat->get();

        // Ubah menjadi Collection, bukan array
        $riwayatTetap = $tugasTetap->map(function ($t) {
            return [
                'id' => $t->id,
                'pemberi_tugas' => $t->pemberi_tugas,
                'tgl_mulai' => $t->tanggal_mulai,
                'tgl_selesai' => $t->tgl_selesai ?? null,
                'nama_mekanik' => $t->nama_mekanik,
                'equipment' => $t->equipment,
                'tag_number' => $t->tag_number,
                'eq_class' => $t->eq_class ?? '-',
                'bom' => $t->bom ?? '-',
                'task_list' => $t->task_list ?? '-',
                'lokasi' => $t->lokasi ?? '-',
                'status' => $t->status ?? '-',
                'validasi_mp' => $t->validasi_mp ?? '-',
                'bukti_foto' => $t->bukti_foto ?? null,
                'jenis' => 'Tugas Tetap',
            ];
        });

        $riwayatDarurat = $tugasDarurat->map(function ($t) {
            return [
                'id' => $t->id,
                'pemberi_tugas' => $t->pemberi_tugas,
                'tgl_mulai' => $t->tgl_mulai,
                'tgl_selesai' => $t->tgl_selesai,
                'nama_mekanik' => $t->nama_mekanik,
                'equipment' => $t->equipment,
                'tag_number' => $t->tag_number,
                'eq_class' => $t->eq_class ?? '-',
                'bom' => $t->bom ?? '-',
                'task_list' => $t->task_list ?? '-',
                'lokasi' => $t->lokasi ?? '-',
                'status' => $t->status ?? '-',
                'validasi_mp' => $t->validasi_mp ?? '-',
                'bukti_foto' => $t->bukti_foto ?? null,
                'jenis' => 'Tugas Darurat',
            ];
        });

        // Gabungkan collection dengan concat, bukan merge
        $riwayat = $riwayatTetap
                    ->concat($riwayatDarurat)
                    ->sortByDesc('tgl_mulai')
                    ->values();

        return view('admin.riwayat-tugas.index', compact('riwayat', 'startDate', 'endDate', 'search'));
    }

    public function show($id)
    {
        $tugas = TugasTetap::find($id) ?? TugasDarurat::find($id);

        if (!$tugas) {
            abort(404);
        }

        return view('admin.riwayat-tugas.show', compact('tugas'));
    }
}
