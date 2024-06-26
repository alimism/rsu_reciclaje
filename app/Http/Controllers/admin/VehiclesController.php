<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehiclecolor;
use App\Models\Vehicleimage;
use App\Models\Vehicletype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiclesController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        // $vehicles = Vehicle::query()
        //     ->from('brandmodels as m')  // Setting alias for the main table
        //     ->select('m.id', 'm.name', 'b.name as brand_name', 'm.code', 'm.description')
        //     ->join('brands as b', 'm.brand_id', '=', 'b.id')  // Using aliases in the join
        //     ->get();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brandSQL = Brand::whereRaw('id IN (select brand_id FROM brandmodels)');
        // $modelSQL = BrandModel::where('brand_id','=',$brandSQL->first()->id)->pluck('name','id');
        $brands = $brandSQL->pluck('name', 'id');
        $models = BrandModel::where('brand_id', '=', $brandSQL->first()->id)->pluck('name', 'id');
        $types = Vehicletype::pluck('name', 'id');
        $colors = Vehiclecolor::pluck('name', 'id');
        return view('admin.vehicles.create', compact('brands', 'models', 'types', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vehicles,name',
            'plate' => 'required|unique:vehicles,plate',
            'code' => 'required|unique:vehicles,code',
        ]);

        $status = 0;

        if (isset($request->status)) {
            $status = 1;
        }


        $vehicle = Vehicle::create($request->except('image') + ['status' => $status]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                try {
                    $image = $request->file('image')->store('public/vehicles_images');
                    $urlImage = Storage::url($image);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'File no pudo ser guardado: ' . $e->getMessage());
                }

                Vehicleimage::create([
                    'image' => $urlImage, 'profile' => '1', 'vehicle_id' => $vehicle->id
                ]);
            } else {
                return redirect()->back()->with('error', 'La imagen no es válida.');
            }
        } else {
            $urlImage = asset('storage/vehicles_images/default_vehicle.png');
        }



        return redirect()->route('admin.vehicles.index')->with('success', 'Vehiculo registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Encuentra el vehículo por su ID y carga sus ocupantes con estado 1 , osea que su asignacion sigue disponible
        $vehicle = Vehicle::with(['occupants' => function ($query) {
            $query->where('status', 1);
        }])->findOrFail($id);

        // Obtiene todos los usuarios con tipo 'Conductor' (usertype_id = 3)
        $conductores = User::where('usertype_id', 3)->get();

        // Obtiene todos los usuarios con tipo 'Recolector' (usertype_id = 4)
        $recolectores = User::where('usertype_id', 4)->get();

        // Obtiene la capacidad del vehículo
        $capacity = $vehicle->capacity;

        $primaryColor = $vehicle->color->name;

        // Retorna la vista 'admin.vehicles.show' con los datos del vehículo, conductores, recolectores y capacidad
        return view('admin.vehicles.show', compact('vehicle', 'conductores', 'recolectores', 'capacity', 'primaryColor'));
    }

    public function assignOccupants(Request $request, $id)
    {
        // Encuentra el vehículo por su ID
        $vehicle = Vehicle::findOrFail($id);

        // Obtiene los ocupantes actuales del vehículo
        $currentOccupants = $vehicle->occupants;

        // Obtiene el ID del conductor del request
        $conductorId = $request->input('conductor');

        // Obtiene los IDs de los recolectores del request
        $recolectoresIds = $request->input('recolectores', []);

        // Combina el ID del conductor y los IDs de recolectores en una colección y filtra elementos vacíos
        $newOccupants = collect([$conductorId])->merge($recolectoresIds)->filter();

        // Validar que la cantidad de nuevos ocupantes no exceda la capacidad del vehículo
        $totalOccupants = $newOccupants->count();
        if ($totalOccupants > $vehicle->capacity) {
            return redirect()->route('admin.vehicles.index')->with('error', 'La cantidad de ocupantes excede la capacidad del vehículo.');
        }

        // Actualiza el estado a 0 de los ocupantes antiguos que no están en la nueva lista de ocupantes
        foreach ($currentOccupants as $occupant) {
            if (!$newOccupants->contains($occupant->user_id)) {
                $occupant->update(['status' => 0]);
            }
        }

        // Asigna el nuevo conductor al vehículo
        if ($conductorId) {
            // Si el conductor ya existe, actualiza su estado a 1
            $existingConductor = $currentOccupants->where('user_id', $conductorId)->where('usertype_id', 3)->first();
            if ($existingConductor) {
                $existingConductor->update(['status' => 1]);
            } else {
                // Si no existe, crea un nuevo registro de ocupante con tipo 'Conductor'
                $vehicle->occupants()->create([
                    'user_id' => $conductorId,
                    'usertype_id' => 3,
                    'status' => 1,
                ]);
            }
        }

        // Asigna los nuevos recolectores al vehículo
        foreach ($recolectoresIds as $recolectorId) {
            // Si el recolector ya existe, actualiza su estado a 1
            $existingRecolector = $currentOccupants->where('user_id', $recolectorId)->where('usertype_id', 4)->first();
            if ($existingRecolector) {
                $existingRecolector->update(['status' => 1]);
            } else {
                // Si no existe, crea un nuevo registro de ocupante con tipo 'Recolector'
                $vehicle->occupants()->create([
                    'user_id' => $recolectorId,
                    'usertype_id' => 4,
                    'status' => 1,
                ]);
            }
        }

        // Redirige al índice de vehículos con un mensaje de éxito
        return redirect()->route('admin.vehicles.index', $id)->with('success', 'Ocupantes asignados correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $vehicle = Vehicle::findOrFail($id);
        $brandSQL = Brand::whereRaw('id IN (select brand_id FROM brandmodels)');
        $brands = $brandSQL->pluck('name', 'id');
        $models = BrandModel::where('brand_id', $vehicle->brand_id)->pluck('name', 'id');
        $types = Vehicletype::pluck('name', 'id');
        $colors = Vehiclecolor::pluck('name', 'id');
        return view('admin.vehicles.edit', compact('vehicle', 'brands', 'models', 'types', 'colors'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:vehicles,name,' . $vehicle->id,
            'plate' => 'required|unique:vehicles,plate,' . $vehicle->id,
            'code' => 'required|unique:vehicles,code,' . $vehicle->id,
        ]);

        $status = 0;

        if (isset($request->status)) {
            $status = 1;
        }

        $vehicle->update($request->except('image') + ['status' => $status]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                try {
                    $image = $request->file('image')->store('public/vehicles_images');
                    $urlImage = Storage::url($image);
                    // Si ya tiene una imagen, elimina la anterior
                    if ($vehicle->vehicleImage) {
                        Storage::delete(str_replace('/storage', 'public', $vehicle->vehicleImage->first()->image));
                        $vehicle->vehicleImage->first()->update(['image' => $urlImage]);
                    } else {
                        Vehicleimage::create([
                            'image' => $urlImage, 'profile' => '1', 'vehicle_id' => $vehicle->id
                        ]);
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'File no pudo ser guardado: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'La imagen no es válida.');
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehiculo actualizado');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $vehicle = Vehicle::find($id);

        $vehicle->delete();


        return redirect()->route('admin.vehicles.index')->with('success', 'Vehiculo eliminado');
    }
}
