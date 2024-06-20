<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Routestatus;
use App\Models\Vehicle;
use App\Models\Vehicleroute;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleroutesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener las programaciones de rutas de vehículos
        $vehicleroutes = VehicleRoute::with(['vehicle', 'route', 'routeStatus'])->get();

        // Obtener los vehículos y rutas para los filtros
        $vehicles = Vehicle::pluck('name', 'id');
        $routes = Route::pluck('name', 'id');
        $routeStatuses = RouteStatus::all();

        return view('admin.vehicleroutes.index', compact('vehicleroutes', 'vehicles', 'routes', 'routeStatuses'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = Route::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $routeStatuses = Routestatus::pluck('name', 'id');
        return view('admin.vehicleroutes.create', compact('routes', 'vehicles', 'routeStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        dd($request->all()); // Añadir esta línea para depuración

        $request->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'time_route' => 'required|date_format:h:i A',
            'routestatus_id' => 'required|integer',
            'route_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($request->date_start);
        $endDate = Carbon::parse($request->date_end);
        $excludeWeekends = $request->has('exclude_weekends');
        $intervalDays = $request->interval_days ?? 1;
        $timeRoute = Carbon::createFromFormat('h:i A', $request->time_route)->format('H:i');

        for ($date = $startDate; $date->lte($endDate); $date->addDays($intervalDays)) {
            if ($excludeWeekends && ($date->isWeekend())) {
                continue;
            }

            Vehicleroute::create([
                'date_route' => $date->format('Y-m-d'),
                'time_route' => $timeRoute,
                'routestatus_id' => $request->routestatus_id,
                'route_id' => $request->route_id,
                'vehicle_id' => $request->vehicle_id,
                'description' => $request->description,
            ]);
        }

        return redirect()->route('admin.vehicleroutes.index')->with('success', 'Programación creada con éxito.');
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
        $vehicleroute = VehicleRoute::findOrFail($id);
        $routes = Route::pluck('name', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        $routeStatuses = RouteStatus::pluck('name', 'id');
        return view('admin.vehicleroutes.edit', compact('vehicleroute', 'routes', 'vehicles', 'routeStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date_route' => 'required|date',
            'time_route' => 'required',
            'routestatus_id' => 'required|integer',
            'route_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'description' => 'nullable|string'
        ]);

        $vehicleroute = VehicleRoute::findOrFail($id);
        $vehicleroute->update($request->all());

        return redirect()->route('admin.vehicleroutes.index')->with('success', 'Ruta del vehículo actualizada con éxito.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicleroute = VehicleRoute::findOrFail($id);
        $vehicleroute->delete();

        return redirect()->route('admin.vehicleroutes.index')->with('success', 'Ruta del vehículo eliminada con éxito.');
    }
}
