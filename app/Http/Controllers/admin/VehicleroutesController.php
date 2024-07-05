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
        $vehicleroutes = VehicleRoute::all();
        $minDate = $vehicleroutes->min('date_route');
        $maxDate = $vehicleroutes->max('date_route');

        return view('admin.vehicleroutes.index', compact('vehicleroutes', 'minDate', 'maxDate'));
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
        try {
            $validatedData = $request->validate([
                'date_start' => 'required|date',
                'date_end' => 'required|date|after_or_equal:date_start',
                'time_route' => 'required|date_format:H:i',  // Cambiado de 'h:i A' a 'H:i'
                'routestatus_id' => 'required|integer',
                'route_id' => 'required|integer',
                'vehicle_id' => 'required|integer',
                'description' => 'nullable|string',
            ]);

            $startDate = Carbon::parse($request->date_start);
            $endDate = Carbon::parse($request->date_end);
            $excludeWeekends = $request->has('exclude_weekends');
            $intervalDays = $request->interval_days ?? 1;
            $timeRoute = $request->time_route;  // Ya no necesitamos convertir el formato

            $createdRoutes = 0;

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
                $createdRoutes++;
            }

            if ($createdRoutes == 0) {
                throw new \Exception('No se crearon rutas. Por favor, revise los parámetros de fecha y exclusión de fines de semana.');
            }

            return redirect()->route('admin.vehicleroutes.index')->with('success', 'Programación creada con éxito.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }
            return redirect()->back()->withErrors($errors)->withInput()->with('validationErrors', $errorMessages);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear la programación: ' . $e->getMessage());
        }
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
        try {
            $validatedData = $request->validate([
                'date_route' => 'required|date',
                'time_route' => 'required|date_format:H:i',
                'routestatus_id' => 'required|integer',
                'route_id' => 'required|integer',
                'vehicle_id' => 'required|integer',
                'description' => 'nullable|string'
            ]);

            $vehicleroute = VehicleRoute::findOrFail($id);
            $vehicleroute->update($validatedData);

            return redirect()->route('admin.vehicleroutes.index')->with('success', 'Ruta del vehículo actualizada con éxito.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }
            return redirect()->back()->withErrors($errors)->withInput()->with('validationErrors', $errorMessages);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la ruta del vehículo: ' . $e->getMessage());
        }
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
