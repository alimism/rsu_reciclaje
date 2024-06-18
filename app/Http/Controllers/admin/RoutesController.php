<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Zone;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::all();

        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::all();
        return view('admin.routes.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'latitude_start' => 'required|numeric',
            'longitude_start' => 'required|numeric',
            'latitude_end' => 'required|numeric',
            'longitude_end' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        Route::create($data);

        return redirect()->route('admin.routes.index')->with('success', 'Ruta creada con éxito.');
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $route = Route::findOrFail($id);
        $assignedZones = $route->zones->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'coords' => $zone->coords->map(function ($coord) {
                    return [
                        'latitude' => $coord->latitude,
                        'longitude' => $coord->longitude
                    ];
                })
            ];
        });

        $availableZones = Zone::whereDoesntHave('routes')->get();

        return view('admin.routes.show', compact('route', 'assignedZones', 'availableZones'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $route = Route::findOrFail($id);
        return view('admin.routes.edit', compact('route'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'latitude_start' => 'required|numeric',
            'longitude_start' => 'required|numeric',
            'latitude_end' => 'required|numeric',
            'longitude_end' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $route = Route::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;

        $route->update($data);

        return redirect()->route('admin.routes.index')->with('success', 'Ruta actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $route = Route::findOrFail($id);
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Ruta eliminada con éxito.');
    }
}
