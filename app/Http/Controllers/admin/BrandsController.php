<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Support\Facades\Storage;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
                try {
                    $image = $request->file('logo')->store('public/brands_logo');
                    $logo = Storage::url($image);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'File could not be saved: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'Uploaded file is not valid.');
            }
        } else {
            $logo = asset('storage/brands_logo/default_brand.png');
        }

        Brand::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $logo
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Marca registrada');
    }


    public function edit(string $id)
    {

        // return $request;

        $brand = Brand::find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id); // Asegúrate de que la marca existe
    
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('public/brands_logo');
            $logo = Storage::url($path);
            // Asegúrate de eliminar el logo anterior si es diferente del predeterminado
            if ($brand->logo && $brand->logo !== asset('storage/brands_logo/default_brand.png') && Storage::exists($brand->logo)) {
                Storage::delete($brand->logo);
            }
            $brand->logo = $logo;
        }
    
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->save();
    
        return redirect()->route('admin.brands.index')->with('success', 'Marca actualizada');
    }
    

    // public function destroy(string $id)
    // {
    //     $brand = Brand::find($id);
    //     if ($brand) {
    //         $brand->delete();
    //     }

    //     return redirect()->route('admin.brands.index');
    // }

    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->route('admin.brands.index')->with('error', 'Marca no encontrada.');
        }

        // Comprobar si hay modelos dependientes
        if ($brand->brandModels()->exists()) {
            return redirect()->route('admin.brands.index')->with('error', 'Marca contiene modelos asociados. No se puede eliminar.');
        }

        // Eliminar el logo si existe en el almacenamiento
        $defaultLogo = 'storage/brands_logo/default_brand.png';
        if ($brand->logo && $brand->logo !== $defaultLogo && Storage::exists($brand->logo)) {
            Storage::delete($brand->logo);
        }

        // Eliminar la marca después de eliminar el logo
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marca eliminada correctamente.');
    }
}
