<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TugasDarurat;
use App\Models\User;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Notifications\TugasBaruNotification;
use App\Jobs\KirimTugasTerjadwalJob;
use App\Jobs\KirimTugasSekarangJob;
use Carbon\Carbon;

class TugasDaruratController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = TugasDarurat::query()
            ->where('pemberi_tugas', Auth::user()->name);

        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->tgl_mulai) {
            $query->whereDate('tgl_mulai', '>=', $request->tgl_mulai);
        }

        if ($request->tgl_selesai) {
            $query->whereDate('tgl_selesai', '<=', $request->tgl_selesai);
        }

        $tugasDarurat = $query->latest()->get();

        return view('admin.kelola-tugas.tugas-darurat.index', compact('tugasDarurat'));
    }

    public function create()
    {
        $mekanik = User::where('role', 'mekanik')->get();
        $equipment = Equipment::all();

        return view('admin.kelola-tugas.tugas-darurat.create', compact('mekanik', 'equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'mekanik_id'   => 'required|array',
            'mekanik_id.*' => ['required', Rule::exists('users', 'id')],
            'equipment_id' => ['required', Rule::exists('equipment', 'id')],
            'bom'          => 'nullable|string|max:255',
            'task_list'    => 'required|string',
            'lokasi'       => 'required|string|max:255',
            'eq_class'     => 'nullable|string|max:255',
            'status'       => ['required', Rule::in(['pending','dikerjakan','selesai'])],
        ]);

        $mulai = Carbon::parse($validated['tgl_mulai']);
        $now = Carbon::now();

        $equipment = Equipment::find($validated['equipment_id']);
        $pemberi = Auth::user()->name;

        foreach ($validated['mekanik_id'] as $mekanik_id) {
            $mekanik = User::find($mekanik_id);

            $tugas = TugasDarurat::create([
                'pemberi_tugas' => $pemberi,
                'tgl_mulai'     => $validated['tgl_mulai'],
                'tgl_selesai'   => $validated['tgl_selesai'],
                'mekanik_id'    => $mekanik->id,
                'nama_mekanik'  => $mekanik->name,
                'equipment_id'  => $equipment->id,
                'equipment'     => $equipment->name,
                'tag_number'    => $equipment->tag_number,
                'eq_class'      => $validated['eq_class'] ?? null,
                'bom'           => $validated['bom'] ?? null,
                'task_list'     => $validated['task_list'],
                'lokasi'        => $validated['lokasi'],
                'status'        => 'pending',
            ]);

            $jenis = 'tugas_darurat';

            // =====================
            // PENJADWALAN TUGAS
            // =====================
            $delay = max($mulai->diffInSeconds($now, false), 0);

            if ($delay > 0) {
                KirimTugasTerjadwalJob::dispatch($tugas, $jenis)->delay($delay);
            } else {
                KirimTugasSekarangJob::dispatch($tugas, $jenis);
            }
        }

        return redirect()->route('admin.kelola-tugas.tugas-darurat.index')
            ->with('success', 'Tugas darurat berhasil dikirim ke semua mekanik yang dipilih.');
    }

    public function edit($id)
    {
        $tugasDarurat = TugasDarurat::findOrFail($id);
        $mekanik = User::where('role', 'mekanik')->get();
        $equipments = Equipment::all();

        return view('admin.kelola-tugas.tugas-darurat.edit', compact('tugasDarurat', 'mekanik', 'equipments'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after_or_equal:tgl_mulai',
            'mekanik_id'   => ['required', Rule::exists('users', 'id')],
            'equipment_id' => ['required', Rule::exists('equipment', 'id')],
            'bom'          => 'nullable|string|max:255',
            'task_list'    => 'required|string',
            'lokasi'       => 'required|string|max:255',
            'eq_class'     => 'nullable|string|max:255',
            'status'       => ['required', Rule::in(['pending','dikerjakan','selesai'])], // ✅ diperbaiki
        ]);

        $tugas_darurat = TugasDarurat::findOrFail($id);
        $mekanik = User::find($validated['mekanik_id']);
        $equipment = Equipment::find($validated['equipment_id']);

        $tugas_darurat->update([
            'tgl_mulai'     => $validated['tgl_mulai'],
            'tgl_selesai'   => $validated['tgl_selesai'],
            'mekanik_id'    => $validated['mekanik_id'],
            'nama_mekanik'  => $mekanik->name,
            'equipment_id'  => $validated['equipment_id'],
            'equipment'     => $equipment->name,
            'tag_number'    => $equipment->tag_number,
            'eq_class'      => $validated['eq_class'] ?? null,
            'bom'           => $validated['bom'] ?? null,
            'task_list'     => $validated['task_list'],
            'lokasi'        => $validated['lokasi'],
            'status'        => $validated['status'] ?? 'pending', // ✅ diperbaiki
        ]);

        return redirect()->route('admin.kelola-tugas.tugas-darurat.index')
            ->with('success', 'Tugas darurat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas_darurat = TugasDarurat::findOrFail($id);
        $tugas_darurat->delete();

        return redirect()->route('admin.kelola-tugas.tugas-darurat.index')
            ->with('success', 'Tugas darurat berhasil dihapus.');
    }
}
