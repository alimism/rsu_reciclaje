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

        // manejo con findOrFail para asegurar que existe el registro antes de continuar con la demas logica
        $vehicle = Vehicle::with('occupants')->findOrFail($id);
        // Filtrar usuarios por tipo 'Conductor' (usertype_id = 3) y 'Recolector' (usertype_id = 4)
        $conductores = User::where('usertype_id', 3)->get();
        $recolectores = User::where('usertype_id', 4)->get();

        return view('admin.vehicles.show', compact('vehicle', 'conductores', 'recolectores'));
    }

    public function assignOccupants(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
    
        // Eliminar todos los ocupantes actuales del vehículo para reutilizar el metodo
        $vehicle->occupants()->delete();
    
        // Asignar los nuevos conductores y recolectores al vehículo
        $conductor = $request->input('conductor');
        $recolectores = $request->input('recolectores', []);
    
        if ($conductor) {
            $vehicle->occupants()->create([
                'user_id' => $conductor,
                'usertype_id' => 3,
                'status' => 1,
            ]);
        }
    
        foreach ($recolectores as $recolector) {
            $vehicle->occupants()->create([
                'user_id' => $recolector,
                'usertype_id' => 4,
                'status' => 1,
            ]);
        }
    
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
    public function update(Request $request, string $id)
    {
        //
        $vehicle = Vehicle::find($id);

        // $vehicle = Vehicle::create();

        $vehicle->update($$request->except('image'));

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                try {
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'File no pudo ser guardado: ' . $e->getMessage());
                }
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
