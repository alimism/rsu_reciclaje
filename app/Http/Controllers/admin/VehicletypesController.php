<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vehicletype;
use Illuminate\Http\Request;

class VehicletypesController extends Controller
{
    public function index()
    {
        $vehicletypes = Vehicletype::all();
        return view('admin.vehicletypes.index', compact('vehicletypes'));
    }

    public function create()
    {
        return view('admin.vehicletypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vehicletypes,name',
            'description' => 'nullable|string',
        ]);

        Vehicletype::create($request->all());

        return redirect()->route('admin.vehicletypes.index')->with('success', 'Tipo de vehiculo creado');
    }

    public function show($id)
    {
        $vehicletype = Vehicletype::findOrFail($id);
        return view('admin.vehicletypes.show', compact('vehicletype'));
    }

    public function edit($id)
    {
        $vehicletype = Vehicletype::findOrFail($id);
        return view('admin.vehicletypes.edit', compact('vehicletype'));
    }

    public function update(Request $request, $id)
    {
        $vehicletype = Vehicletype::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:vehicletypes,name,' . $vehicletype->id,
            'description' => 'nullable|string',
        ]);

        $vehicletype->update($request->all());

        return redirect()->route('admin.vehicletypes.index')->with('success', 'Tipo de vehiculo actualizado');
    }

    public function destroy($id)
    {
        $vehicletype = VehicleType::findOrFail($id);

        // Validación para verificar si el tipo de vehículo está siendo utilizado
        $vehiclesWithType = Vehicle::where('type_id', $vehicletype->id)->count();
        if ($vehiclesWithType > 0) {
            return redirect()->route('admin.vehicletypes.index')->with('error', 'Tipo de vehiculo contiene vehiculos asociados. No se puede eliminar.');
        }

        $vehicletype->delete();

        return redirect()->route('admin.vehicletypes.index')->with('success', 'Tipo de vehiculo eliminado');
    }
}
