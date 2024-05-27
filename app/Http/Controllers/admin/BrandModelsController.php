<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use App\Models\Model;

class BrandModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $models = BrandModel::all();
        $models = BrandModel::query()
            ->from('brandmodels as m')  // Setting alias for the main table
            ->select('m.id', 'm.name', 'b.name as brand_name', 'm.code', 'm.description')
            ->join('brands as b', 'm.brand_id', '=', 'b.id')  // Using aliases in the join
            ->get();

        return view('admin.models.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::pluck('name', 'id');
        return view('admin.models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        BrandModel::create($request->all());
        return redirect()->route('admin.models.index')->with('success', 'Modelo registrado');
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
        $model = BrandModel::findOrFail($id);
        $brands = Brand::pluck('name', 'id');
        return view('admin.models.edit', compact('model', 'brands'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $model = BrandModel::find($id);

        $model->update($request->all());

        return redirect()->route('admin.models.index')->with('success', 'Modelo actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $model = BrandModel::find($id);

        $model->delete();


        return redirect()->route('admin.models.index')->with('success', 'Modelo eliminado');
    }

    public function modelsByBrand(string $id)
    {
        $models = BrandModel::where('brand_id', $id)->get();
        return $models;
    }
}
