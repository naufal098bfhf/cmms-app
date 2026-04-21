<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasTetap;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TugasTetapController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // ===============================
    // AUTO KIRIM TUGAS (FIX TOTAL)
    // ===============================
    public function cekDanKirimTugas()
    {
        $hariIni = Carbon::now();

        $tugas = TugasTetap::all();

        foreach ($tugas as $item) {

            // skip jika sudah kirim hari ini
            if (
                $item->last_sent &&
                Carbon::parse($item->last_sent)->isToday()
            ) {
                continue;
            }

            $terkirim = false;

            // =====================
            // MINGGUAN
            // =====================
            if (
                $item->jenis_tugas == 'mingguan' &&
                strtolower($item->hari_mingguan) == strtolower($hariIni->locale('id')->dayName)
            ) {
                Notifikasi::create([
                    'user_id' => $item->mekanik_id,
                    'pesan' => "Tugas mingguan: {$item->equipment}",
                    'link' => route('mekanik.tugas-tetap.index'),
                    'read' => false,
                ]);

                $terkirim = true;
            }

            // =====================
            // BULANAN (FIX TOTAL)
            // =====================
            if (
                $item->jenis_tugas == 'bulanan' &&
                !is_null($item->tanggal_bulanan) &&
                (int)$item->tanggal_bulanan === (int)$hariIni->day
            ) {

                // cek duplicate
                $sudahAda = TugasTetap::where('mekanik_id', $item->mekanik_id)
                    ->whereDate('tanggal_mulai', $hariIni)
                    ->where('equipment_id', $item->equipment_id)
                    ->exists();

                if (!$sudahAda) {

                    // buat task baru
                    TugasTetap::create([
                        'pemberi_tugas' => $item->pemberi_tugas,
                        'mekanik_id' => $item->mekanik_id,
                        'nama_mekanik' => $item->nama_mekanik,
                        'equipment_id' => $item->equipment_id,
                        'equipment' => $item->equipment,
                        'tag_number' => $item->tag_number,
                        'jenis_tugas' => $item->jenis_tugas,
                        'tanggal_mulai' => $hariIni,
                        'eq_class' => $item->eq_class,
                        'bom' => $item->bom,
                        'task_list' => $item->task_list,
                        'lokasi' => $item->lokasi,
                        'status' => 'pending',
                    ]);

                    Notifikasi::create([
                        'user_id' => $item->mekanik_id,
                        'pesan' => "Tugas bulanan: {$item->equipment}",
                        'link' => route('mekanik.tugas-tetap.index'),
                        'read' => false,
                    ]);

                    $terkirim = true;
                }
            }

            // =====================
            // TAHUNAN
            // =====================
            if (
                $item->jenis_tugas == 'tahunan' &&
                $item->tanggal_tahunan == $hariIni->format('Y-m-d')
            ) {
                Notifikasi::create([
                    'user_id' => $item->mekanik_id,
                    'pesan' => "Tugas tahunan: {$item->equipment}",
                    'link' => route('mekanik.tugas-tetap.index'),
                    'read' => false,
                ]);

                $terkirim = true;
            }

            // update last_sent hanya jika kirim
            if ($terkirim) {
                $item->update([
                    'last_sent' => $hariIni->toDateString()
                ]);
            }
        }
    }

    // ===============================
    // INDEX
    // ===============================
    public function index(Request $request)
    {
        $this->cekDanKirimTugas();

        $query = TugasTetap::query()
            ->where('pemberi_tugas', Auth::user()->name);

        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $tugasTetap = $query->latest()->get();

        return view('admin.kelola-tugas.tugas-tetap.index', compact('tugasTetap'));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        $mekanik = User::where('role', 'mekanik')->get();
        $equipment = Equipment::all();

        return view('admin.kelola-tugas.tugas-tetap.create', compact('mekanik', 'equipment'));
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_tugas'      => ['required', Rule::in(['mingguan', 'bulanan', 'tahunan'])],
            'mekanik_id'       => ['required', 'array'],
            'mekanik_id.*'     => ['exists:users,id'],
            'equipment_id'     => ['required', Rule::exists('equipment', 'id')],
            'eq_class'         => 'required|string|max:255',
            'task_list'        => 'required|string',
            'lokasi'           => 'required|string|max:255',
            'bom'              => 'nullable|string|max:255',
            'status'           => ['nullable', Rule::in(['pending', 'dikerjakan', 'selesai'])],
            'hari_mingguan'    => 'nullable|string',
            'tanggal_bulanan'  => 'nullable|integer|min:1|max:31',
            'tanggal_tahunan'  => 'nullable|date',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        foreach ($validated['mekanik_id'] as $mekanikId) {

            $mekanik = User::find($mekanikId);

            TugasTetap::create([
                'pemberi_tugas'   => Auth::user()->name,
                'mekanik_id'      => $mekanikId,
                'nama_mekanik'    => $mekanik ? $mekanik->name : null,
                'equipment_id'    => $equipment->id,
                'equipment'       => $equipment->name,
                'tag_number'      => $equipment->tag_number,
                'jenis_tugas'     => $validated['jenis_tugas'],
                'hari_mingguan'   => $validated['hari_mingguan'] ?? null,
                'tanggal_bulanan' => $validated['tanggal_bulanan'] ?? null,
                'tanggal_tahunan' => $validated['tanggal_tahunan'] ?? null,
                'eq_class'        => $validated['eq_class'],
                'bom'             => $validated['bom'] ?? null,
                'task_list'       => $validated['task_list'],
                'lokasi'          => $validated['lokasi'],
                'status'          => $validated['status'] ?? 'pending',
            ]);

            Notifikasi::create([
                'user_id' => $mekanikId,
                'pesan' => "Tugas baru: {$equipment->name}",
                'link' => route('mekanik.tugas-tetap.index'),
                'read' => false,
            ]);
        }

        return redirect()->route('admin.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tugas = TugasTetap::findOrFail($id);
        $mekanik = User::where('role', 'mekanik')->get();
        $equipment = Equipment::all();

        return view('admin.kelola-tugas.tugas-tetap.edit', compact('tugas', 'mekanik', 'equipment'));
    }

    public function update(Request $request, $id)
    {
        $tugas = TugasTetap::findOrFail($id);

        $validated = $request->validate([
            'jenis_tugas' => ['required', Rule::in(['mingguan', 'bulanan', 'tahunan'])],
            'mekanik_id'  => ['required', Rule::exists('users', 'id')],
            'equipment_id'=> ['required', Rule::exists('equipment', 'id')],
            'status'      => ['nullable', Rule::in(['pending', 'dikerjakan', 'selesai'])],
        ]);

        $equipment = Equipment::find($validated['equipment_id']);
        $mekanik   = User::find($validated['mekanik_id']);

        $tugas->update([
            'mekanik_id'   => $validated['mekanik_id'],
            'nama_mekanik' => $mekanik->name ?? '-',
            'equipment_id' => $validated['equipment_id'],
            'equipment'    => $equipment->name,
            'status'       => $validated['status'] ?? 'pending',
        ]);

        return redirect()->route('admin.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TugasTetap::findOrFail($id)->delete();

        return redirect()
            ->route('admin.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas berhasil dihapus');
    }
}
