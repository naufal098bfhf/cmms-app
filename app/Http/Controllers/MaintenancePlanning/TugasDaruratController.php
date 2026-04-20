<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasDarurat;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TugasDaruratController extends Controller
{
    /**
     * Menampilkan daftar tugas darurat.
     */
    public function index()
    {
        $tugasDarurat = TugasDarurat::with('mekanik')->latest()->get();
        return view('maintenance-planning.kelola-tugas.tugas-darurat.index', compact('tugasDarurat'));
    }

    /**
     * Form tambah tugas darurat.
     */
    public function create()
    {
        $mekanik = User::where('role', 'mekanik')->where('is_active', 1)->get();
        $equipment = Equipment::all();
        return view('maintenance-planning.kelola-tugas.tugas-darurat.create', compact('mekanik', 'equipment'));
    }

    /**
     * Simpan tugas darurat baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTugas($request);

        try {
            $mekanik = User::select('id', 'name')->find($validated['mekanik_id']);
            $equipment = Equipment::select('id', 'name', 'tag_number')->find($validated['equipment_id']);

            $tugas = TugasDarurat::create([
                'pemberi_tugas' => Auth::user()->name,
                'mekanik_id'    => $validated['mekanik_id'],
                'nama_mekanik'  => $mekanik->name ?? '-',
                'tgl_mulai'     => $validated['tgl_mulai'],
                'tgl_selesai'   => $validated['tgl_selesai'],
                'equipment'     => $equipment->name ?? '-',
                'tag_number'    => $equipment->tag_number ?? '',
                'eq_class'      => $validated['eq_class'] ?? null,
                'task_list'     => $validated['task_list'],
                'lokasi'        => $validated['lokasi'],
                'bom'           => $validated['bom'] ?? null,
                'status'        => $validated['status'],
            ]);

            $tanggalSekarang = Carbon::now()->toDateString();
            $tanggalMulai = Carbon::parse($validated['tgl_mulai'])->toDateString();

            if ($tanggalMulai === $tanggalSekarang) {
                Notifikasi::create([
                    'user_id' => $mekanik->id,
                    'pesan' => 'Ada tugas darurat baru untukmu: ' . ($equipment->name ?? '-'),
                    'link' => route('mekanik.tugas-darurat.show', $tugas->id),
                    'read' => false,
                ]);

                $tugas->update(['is_sent' => true]);
            } else {
                $tugas->update([
                    'notifikasi_dijadwalkan' => $tanggalMulai,
                    'is_sent' => false
                ]);
            }

            return redirect()->route('maintenance-planning.kelola-tugas.tugas-darurat.index')
                ->with('success', 'Tugas darurat berhasil ditambahkan dan akan dikirim ke mekanik sesuai jadwal.');
        } catch (\Exception $e) {
            Log::error('Gagal tambah tugas darurat: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form edit tugas darurat.
     */
    public function edit($id)
    {
        $tugasDarurat = TugasDarurat::findOrFail($id);
        $mekanik = User::where('role', 'mekanik')->where('is_active', 1)->get();
        $equipment = Equipment::all();

        return view('maintenance-planning.kelola-tugas.tugas-darurat.edit', compact('tugasDarurat', 'mekanik', 'equipment'));
    }

    /**
     * Update data tugas darurat.
     */
    public function update(Request $request, $id)
    {
        $tugasDarurat = TugasDarurat::findOrFail($id);
        $validated = $this->validateTugas($request);

        try {
            $mekanik = User::select('id', 'name')->find($validated['mekanik_id']);
            $equipment = Equipment::select('id', 'name', 'tag_number')->find($validated['equipment_id']);

            $tugasDarurat->update([
                'tgl_mulai'    => $validated['tgl_mulai'],
                'tgl_selesai'  => $validated['tgl_selesai'],
                'mekanik_id'   => $validated['mekanik_id'],
                'nama_mekanik' => $mekanik->name ?? '-',
                'equipment'    => $equipment->name ?? '-',
                'tag_number'   => $equipment->tag_number ?? '',
                'eq_class'     => $validated['eq_class'] ?? null,
                'task_list'    => $validated['task_list'],
                'lokasi'       => $validated['lokasi'],
                'bom'          => $validated['bom'] ?? null,
                'status'       => $validated['status'],
            ]);

            $tanggalSekarang = Carbon::now()->toDateString();
            $tanggalMulai = Carbon::parse($validated['tgl_mulai'])->toDateString();

            if ($tanggalMulai === $tanggalSekarang) {
                Notifikasi::create([
                    'user_id' => $mekanik->id,
                    'pesan' => 'Tugas darurat telah diperbarui: ' . ($equipment->name ?? '-'),
                    'link' => route('mekanik.tugas-darurat.show', $tugasDarurat->id),
                    'read' => false,
                ]);

                $tugasDarurat->update(['is_sent' => true]);
            } else {
                $tugasDarurat->update([
                    'notifikasi_dijadwalkan' => $tanggalMulai,
                    'is_sent' => false
                ]);
            }

            return redirect()->route('maintenance-planning.kelola-tugas.tugas-darurat.index')
                ->with('success', 'Tugas darurat berhasil diperbarui dan dikirim ke mekanik.');
        } catch (\Exception $e) {
            Log::error('Gagal update tugas darurat: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 🔧 FIX ERROR: method destroy (WAJIB ADA karena route DELETE memanggil destroy)
     */
    public function destroy($id)
    {
        $tugasDarurat = TugasDarurat::findOrFail($id);

        $tugasDarurat->delete();

        return redirect()
            ->route('maintenance-planning.kelola-tugas.tugas-darurat.index')
            ->with('success', 'Tugas darurat berhasil dihapus.');
    }

    /**
     * Validasi umum untuk store dan update.
     */
    private function validateTugas(Request $request)
    {
        return $request->validate([
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date',
            'mekanik_id'   => 'required|exists:users,id',
            'equipment_id' => 'required|exists:equipment,id',
            'eq_class'     => 'nullable|string|max:100',
            'task_list'    => 'required|string',
            'lokasi'       => 'required|string|max:255',
            'bom'          => 'nullable|string|max:255',
            'status'       => 'required|in:release_order,dikerjakan,selesai',
        ]);
    }
}
