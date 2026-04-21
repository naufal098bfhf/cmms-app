<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('tag_number', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter kondisi
        if ($request->filter) {
            $query->where('kondisi', $request->filter);
        }

        // Pagination
        $equipment = $query->latest()->paginate(10);
        $equipment->appends($request->all());

        return view('admin.kelola-equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('admin.kelola-equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',      // varchar(50)
            'tag_number' => 'required|integer|unique:equipment,tag_number',
            'tanggal_masuk_aset' => 'required|date',
            'kondisi' => 'nullable|string|max:20',   // varchar(20)
            'bom' => 'nullable|string|max:255',      // varchar(255)
        ]);

        Equipment::create($validated);

        return redirect()->route('admin.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.kelola-equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'tag_number' => 'required|integer|unique:equipment,tag_number,' . $id,
            'tanggal_masuk_aset' => 'required|date',
            'kondisi' => 'nullable|string|max:20',
            'bom' => 'nullable|string|max:255',
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update($validated);

        return redirect()->route('admin.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil diupdate!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('admin.kelola-equipment.index')
                         ->with('success', 'Data Equipment berhasil dihapus!');
    }
}
