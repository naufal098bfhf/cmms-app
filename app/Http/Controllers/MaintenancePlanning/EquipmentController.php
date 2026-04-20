<?php

namespace App\Http\Controllers\MaintenancePlanning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::latest()->paginate(10);
        return view('maintenance-planning.kelola-equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('maintenance-planning.kelola-equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',      // varchar(50)
            'tag_number' => ['required', 'integer', 'unique:equipment,tag_number'],
            'tanggal_masuk_aset' => 'required|date',
            'kondisi' => 'required|string|in:baik,rusak|max:20',   // sama seperti Admin
            'bom' => 'nullable|string|max:255',      // varchar(255)
        ]);

        Equipment::create($validated);

        return redirect()->route('maintenance-planning.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('maintenance-planning.kelola-equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'tag_number' => ['required', 'integer', 'unique:equipment,tag_number,' . $id],
            'tanggal_masuk_aset' => 'required|date',
            'kondisi' => 'required|string|in:baik,rusak|max:20', // sama seperti Admin
            'bom' => 'nullable|string|max:255',
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update($validated);

        return redirect()->route('maintenance-planning.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil diupdate!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('maintenance-planning.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil dihapus!');
    }
}
