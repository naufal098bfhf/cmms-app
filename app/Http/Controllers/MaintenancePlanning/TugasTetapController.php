<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasTetap;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TugasTetapController extends Controller
{
    public function index()
    {
        $this->cekDanKirimTugas();

        $tugasTetap = TugasTetap::with(['equipmentRelasi', 'mekanik'])->get();
        return view('maintenance-planning.kelola-tugas.tugas-tetap.index', compact('tugasTetap'));
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

                // cek apakah sudah ada tugas hari ini
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

            // update hanya jika kirim
            if ($terkirim) {
                $item->update([
                    'last_sent' => $hariIni->toDateString()
                ]);
            }
        }
    }

    public function create()
    {
        $mekanik = User::where('role', 'mekanik')->get();
        $equipment = Equipment::all();

        return view('maintenance-planning.kelola-tugas.tugas-tetap.create', compact('mekanik', 'equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_tugas' => 'required|string',
            'mekanik_id' => 'required|array',
            'mekanik_id.*' => 'exists:users,id',
            'equipment_id' => 'required|exists:equipment,id',
            'tag_number' => 'nullable|string',
            'eq_class' => 'nullable|string',
            'task_list' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'bom' => 'nullable|string',
            'status' => 'required|in:release_order,dikerjakan,selesai',
            'hari_mingguan' => 'nullable|string',
            'tanggal_bulanan' => 'nullable|integer',
            'tanggal_tahunan' => 'nullable|date',
        ]);

        $validated['pemberi_tugas'] = Auth::user()->name;

        if ($validated['status'] === 'release_order') {
            $validated['status'] = 'pending';
        }

        $equipment = Equipment::find($validated['equipment_id']);

        foreach ($validated['mekanik_id'] as $mekanikId) {

            if (!$mekanikId) continue;

            $mekanik = User::find($mekanikId);

            $data = $validated;
            $data['mekanik_id'] = $mekanikId;
            $data['nama_mekanik'] = $mekanik->name ?? '-';
            $data['equipment'] = $equipment->name ?? '-';
            $data['tag_number'] = $equipment->tag_number ?? '-';

            $tanggalMulai = Carbon::now();

            // mingguan
            if ($validated['jenis_tugas'] == 'mingguan' && !empty($validated['hari_mingguan'])) {
                $hariMap = [
                    'senin' => Carbon::MONDAY,
                    'selasa' => Carbon::TUESDAY,
                    'rabu' => Carbon::WEDNESDAY,
                    'kamis' => Carbon::THURSDAY,
                    'jumat' => Carbon::FRIDAY,
                    'sabtu' => Carbon::SATURDAY,
                    'minggu' => Carbon::SUNDAY,
                ];

                if (isset($hariMap[$validated['hari_mingguan']])) {
                    $tanggalMulai = Carbon::now()->next($hariMap[$validated['hari_mingguan']]);
                }
            }

            // bulanan
            if ($validated['jenis_tugas'] == 'bulanan' && !empty($validated['tanggal_bulanan'])) {
                $tanggalMulai = Carbon::now()->day($validated['tanggal_bulanan']);

                if ($tanggalMulai->isPast()) {
                    $tanggalMulai->addMonth();
                }
            }

            // tahunan
            if ($validated['jenis_tugas'] == 'tahunan' && !empty($validated['tanggal_tahunan'])) {
                $tanggalMulai = Carbon::parse($validated['tanggal_tahunan']);
            }

            $data['tanggal_mulai'] = $tanggalMulai;

            TugasTetap::create($data);

            Notifikasi::create([
                'user_id' => $mekanikId,
                'pesan' => "Tugas tetap baru untuk {$equipment->name}",
                'link' => route('mekanik.tugas-tetap.index'),
                'read' => false,
            ]);
        }

        return redirect()->route('maintenance-planning.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas tetap berhasil dibuat.');
    }

    public function edit($id)
    {
        $tugas = TugasTetap::findOrFail($id);
        $mekanik = User::where('role', 'mekanik')->get();
        $equipment = Equipment::all();

        return view('maintenance-planning.kelola-tugas.tugas-tetap.edit', compact('tugas', 'mekanik', 'equipment'));
    }

    public function update(Request $request, $id)
    {
        $tugas = TugasTetap::findOrFail($id);

        $validated = $request->validate([
            'jenis_tugas' => 'required|string',
            'mekanik_id' => 'required|array',
            'mekanik_id.*' => 'exists:users,id',
            'equipment_id' => 'required|exists:equipment,id',
            'status' => 'required|in:release_order,dikerjakan,selesai',
        ]);

        if ($validated['status'] === 'release_order') {
            $validated['status'] = 'pending';
        }

        $equipment = Equipment::find($validated['equipment_id']);
        $mekanikId = (int) $validated['mekanik_id'][0];
        $mekanik = User::find($mekanikId);

        $tugas->update([
            'jenis_tugas' => $validated['jenis_tugas'],
            'mekanik_id' => $mekanikId,
            'nama_mekanik' => $mekanik->name ?? '-',
            'equipment_id' => $validated['equipment_id'],
            'equipment' => $equipment->name ?? '-',
            'status' => $validated['status'],
        ]);

        return redirect()->route('maintenance-planning.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas diperbarui.');
    }

    public function destroy($id)
    {
        TugasTetap::findOrFail($id)->delete();

        return redirect()->route('maintenance-planning.kelola-tugas.tugas-tetap.index')
            ->with('success', 'Tugas dihapus.');
    }
}
