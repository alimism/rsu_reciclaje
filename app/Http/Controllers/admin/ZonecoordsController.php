<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\Zonecoord;
use Illuminate\Http\Request;

class ZonecoordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return '<h1>Google Maps</h1>';


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $zoneId = $request->input('zone_id');
        $latitudes = $request->input('latitude');
        $longitudes = $request->input('longitude');
    
        if (!empty($latitudes) && !empty($longitudes)) {
            Zonecoord::where('zone_id', $zoneId)->delete(); // Eliminar coordenadas existentes
    
            for ($i = 0; $i < count($latitudes); $i++) {
                Zonecoord::create([
                    'zone_id' => $zoneId,
                    'latitude' => $latitudes[$i],
                    'longitude' => $longitudes[$i],
                ]);
            }
        }
    
        return redirect()->route('admin.zones.show', $zoneId)->with('success', 'Coordenadas actualizadas');
    }
    


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $zone = Zone::findOrFail($id);
        $coords = Zonecoord::where('zone_id', $id);
        return view('admin.zones.show', compact('zone', 'coords'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $zone = Zone::with(['coords' => function ($query) {
            $query->latest()->select('id', 'zone_id', 'latitude', 'longitude');
        }])->findOrFail($id);

        return view('admin.zonecoords.create', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
