<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Maintenance;
use App\Models\MaintenanceActivity;
use App\Models\MaintenanceSchedule;
use App\Models\Vehicle;

class MaintenancesController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::all();
        return view('admin.maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        return view('admin.maintenances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        Maintenance::create($request->all());

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance created successfully.');
    }

    public function edit(Maintenance $maintenance)
    {
        return view('admin.maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $maintenance->update($request->all());

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance actualizado');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance borrado');
    }

    public function show(Maintenance $maintenance)
    {
        $schedules = $maintenance->schedules;
        return view('admin.maintenances.show', compact('maintenance', 'schedules'));
    }

    public function createSchedule(Maintenance $maintenance)
    {
        $vehicles = Vehicle::all();
        return view('admin.maintenances.create_schedule', compact('maintenance', 'vehicles'));
    }

    public function storeSchedule(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'vehicle_id' => 'required',
            'type' => 'required'
        ]);

        // Validar que los horarios no se solapen
        if ($this->checkOverlap($maintenance->id, $validated['day_of_week'], $validated['start_time'], $validated['end_time'], $validated['vehicle_id'])) {
            return redirect()->back()->with('error', 'El horario se solapa con otro existente.');
        }

        // Log::info('Validated Data:', $validated);

        $schedule = $maintenance->schedules()->create($validated);

        // Log::info('Created Horario:', $schedule->toArray());

        return redirect()->route('admin.maintenances.show', $maintenance->id)->with('success', 'Horario creado');
    }

    public function editSchedule(Maintenance $maintenance, MaintenanceSchedule $schedule)
    {
        $vehicles = Vehicle::all();
        return view('admin.maintenances.edit_schedule', compact('maintenance', 'schedule', 'vehicles'));
    }

    public function updateSchedule(Request $request, Maintenance $maintenance, MaintenanceSchedule $schedule)
    {
        $validated = $request->validate([
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'vehicle_id' => 'required',
            'type' => 'required'
        ]);

        // Validar que los horarios no se solapen, excluyendo el horario actual
        if ($this->checkOverlap($maintenance->id, $validated['day_of_week'], $validated['start_time'], $validated['end_time'], $validated['vehicle_id'], $schedule->id)) {
            return redirect()->back()->with('error', 'El horario se solapa con otro existente.');
        }

        // Agregar log para verificar los datos recibidos
        // Log::info('Validated Data:', $validated);

        $schedule->update($validated);

        // Agregar log para verificar si el horario se actualizó correctamente
        // Log::info('Updated Horario:', $schedule->toArray());

        return redirect()->route('admin.maintenances.show', $maintenance->id)->with('success', 'Horario actualizado');
    }

    public function destroySchedule(Maintenance $maintenance, MaintenanceSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.maintenances.show', $maintenance->id)->with('success', 'Horario borrado.');
    }

    private function getDayOfWeek($date)
{
    $days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $dayOfWeek = date('w', strtotime($date));
    return $days[$dayOfWeek];
}


    public function storeActivity(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'schedule_id' => 'required|exists:maintenance_schedules,id'
        ]);
    
        // Obtener el día de la semana de la fecha de la actividad
        $activityDayOfWeek = $this->getDayOfWeek($validated['date']);
    
        // Obtener el schedule correspondiente
        $schedule = MaintenanceSchedule::find($validated['schedule_id']);
    
        // Verificar que el día de la semana de la actividad coincida con el day_of_week del schedule
        if ($activityDayOfWeek !== $schedule->day_of_week) {
            return redirect()->back()->with('error', 'El día de la semana de la actividad no coincide con el del horario.');
        }
    
        // Verificar que la fecha de la actividad esté entre el start_date y end_date del mantenimiento
        if ($validated['date'] < $maintenance->start_date || $validated['date'] > $maintenance->end_date) {
            return redirect()->back()->with('error', 'La fecha de la actividad debe estar dentro del rango del mantenimiento.');
        }
    
        $activity = new MaintenanceActivity($validated);
        $maintenance->activities()->save($activity);
    
        return redirect()->route('admin.maintenances.show', $maintenance->id)->with('success', 'Actividad creada.');
    }
    

// Añadir el método en MaintenancesController
public function getActivities(Request $request, Maintenance $maintenance)
{
    $activities = MaintenanceActivity::where('maintenance_id', $maintenance->id)
                    ->where('schedule_id', $request->schedule_id)
                    ->get();

    $schedule_id = $request->schedule_id;

    return view('admin.maintenances.activities', compact('maintenance', 'activities', 'schedule_id'));
}



    public function destroyActivity(Maintenance $maintenance, MaintenanceActivity $activity)
    {
        $activity->delete();

        return redirect()->route('admin.maintenances.show', $maintenance->id)->with('success', 'Actividad eliminada.');
    }



    private function checkOverlap($maintenanceId, $dayOfWeek, $startTime, $endTime, $vehicleId, $excludeScheduleId = null)
    {
        $query = MaintenanceSchedule::where('maintenance_id', $maintenanceId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }
}
