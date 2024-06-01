<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usertype;
use Illuminate\Http\Request;

class UsertypesController extends Controller
{

    public function index()
    {
        $usertypes = Usertype::all();
        return view('admin.usertypes.index', compact('usertypes'));
    }

    public function create()
    {
        return view('admin.usertypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:usertypes,name',
            'description' => 'nullable|string',
        ]);

        Usertype::create($request->all());

        return redirect()->route('admin.usertypes.index')->with('success', 'Tipo de usuario creado');
    }

    public function show($id)
    {
        $usertype = Usertype::findOrFail($id);
        return view('admin.usertypes.show', compact('usertype'));
    }

    public function edit($id)
    {
        $usertype = Usertype::findOrFail($id);
        return view('admin.usertypes.edit', compact('usertype'));
    }

    public function update(Request $request, $id)
    {
        $usertype = Usertype::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:usertypes,name,' . $usertype->id,
            'description' => 'nullable|string',
        ]);

        $usertype->update($request->all());

        return redirect()->route('admin.usertypes.index')->with('success', 'Tipo de usuario actualizado');
    }

    public function destroy($id)
    {
        $usertype = Usertype::findOrFail($id);

        // Validación para verificar si el tipo de usuario está siendo utilizado
        $usersWithUserType = User::where('usertype_id', $usertype->id)->count();
        if ($usersWithUserType > 0) {
            return redirect()->route('admin.usertypes.index')->with('error', 'Tipo de usuario contiene usuarios asociados. No se puede eliminar.');
        }

        $usertype->delete();

        return redirect()->route('admin.usertypes.index')->with('success', 'Tipo de usuario eliminado');
    }

}
