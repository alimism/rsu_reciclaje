<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\Zonecoord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZonesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::all();

        $zonesMap = DB::table('zones')
            ->leftJoin('zonecoords', 'zones.id', '=', 'zonecoords.zone_id')
            ->select('zones.name as zone', 'zonecoords.latitude', 'zonecoords.longitude')
            ->get();

        // Agrupa las coordenadas por zona
        $groupedZones = $zonesMap->groupBy('zone');

        $perimeter = $groupedZones->map(function ($zone) {
            $coords = $zone->map(function ($item) {
                return [
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                ];
            })->toArray(); // Convertir la colección de coordenadas en una matriz

            return [
                'name' => $zone[0]->zone, // Cambiar 'zone' por 'name'
                'coords' => $coords,
            ];
        })->values(); // Reindexar las claves numéricas del resultado

        return view('admin.zones.index', compact('zones', 'perimeter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.zones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Zone::create($request->all());
        return redirect()->route('admin.zones.index')->with('success', 'Zona creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $zone = Zone::findOrFail($id);
        // $zonecoords = Zonecoord::all();
        // Filtra las coordenadas que pertenecen a la zona específica
        $zonecoords = Zonecoord::where('zone_id', $id)->get();

        return view('admin.zones.show', compact('zone', 'zonecoords'));
    }

    public function edit($id)
    {
        $zone = Zone::findOrFail($id);
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:zones,name,' . $zone->id,
            'description' => 'nullable|string',
        ]);

        $zone->update($request->all());

        return redirect()->route('admin.zones.index')->with('success', 'Tipo de vehiculo actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
