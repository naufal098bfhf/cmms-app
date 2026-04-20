<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $data = Equipment::latest()->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = Equipment::create($request->all());

        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(Equipment::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());

        return response()->json($equipment);
    }

    public function destroy($id)
    {
        Equipment::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}
