<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Vehiclecolor;
use Illuminate\Http\Request;

class VehiclecolorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiclecolors = Vehiclecolor::all();
        return view('admin.vehiclecolors.index', compact('vehiclecolors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehiclecolors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vehiclecolors,name',
            'description' => 'nullable|string',
        ]);

        Vehiclecolor::create($request->all());

        return redirect()->route('admin.vehiclecolors.index')->with('success', 'Color de vehiculo creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $vehiclecolor = Vehiclecolor::findOrFail($id);
        return view('admin.vehiclecolors.edit', compact('vehiclecolor'));
    }

    public function update(Request $request, $id)
    {
        $vehiclecolor = Vehiclecolor::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:vehiclecolors,name,' . $vehiclecolor->id,
            'description' => 'nullable|string',
        ]);

        $vehiclecolor->update($request->all());

        return redirect()->route('admin.vehiclecolors.index')->with('success', 'Color de vehiculo actualizado');
    }

    public function destroy($id)
    {
        $vehiclecolor = Vehiclecolor::findOrFail($id);

        // Validación para verificar si el tipo de vehículo está siendo utilizado
        $vehiclesWithColor = Vehicle::where('color_id', $vehiclecolor->id)->count();
        if ($vehiclesWithColor > 0) {
            return redirect()->route('admin.vehiclecolors.index')->with('error', 'Color de vehiculo contiene vehiculos asociados. No se puede eliminar.');
        }

        $vehiclecolor->delete();

        return redirect()->route('admin.vehiclecolors.index')->with('success', 'Color de vehiculo eliminado');
    }
}
