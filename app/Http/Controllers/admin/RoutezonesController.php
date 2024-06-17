<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Zone;
use Illuminate\Http\Request;

class RoutezonesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function assignZone(Request $request, $routeId)
    {
        $route = Route::findOrFail($routeId);
        $zoneId = $request->input('zone_id');
        $zone = Zone::findOrFail($zoneId);

        $route->zones()->attach($zone);

        return redirect()->route('admin.routes.show', $routeId)->with('success', 'Zona asignada con éxito.');
    }

    public function unassignZone($routeId, $zoneId)
    {
        $route = Route::findOrFail($routeId);
        $zone = Zone::findOrFail($zoneId);

        $route->zones()->detach($zone);

        return redirect()->route('admin.routes.show', $routeId)->with('success', 'Zona quitada con éxito.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
