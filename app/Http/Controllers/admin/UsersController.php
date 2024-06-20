<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usertype;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::all();
        $users = User::where('usertype_id','!=',2)->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        // $usertype = Usertype::pluck('name', 'id');
        $usertype = Usertype::where('id', '!=', 2)->pluck('name', 'id');
        $zone = Zone::pluck('name', 'id');
        return view('admin.users.create', compact('usertype', 'zone'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // 'name' => 'required|string|max:255',
        // 'lastname' => 'required|string|max:255',
        // 'dni' => 'required|string|max:20',
        // 'birthdate' => 'required|date|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
        // 'license' => 'required|string|max:255',
        // 'address' => 'required|string|max:255',
        // 'email' => 'required|string|email|max:255|unique:users',
        // 'password' => 'required|string|min:8|confirmed',
        // 'profile_photo_path' => 'nullable|string|max:255',
        // 'usertype_id' => 'required|integer',
        // 'zone_id' => 'required|integer',
        //
        $request->validate(
            [
                'email' => 'unique:users',
                'dni' => 'unique:users',
                //'license' => 'unique:users',
            ]
        );

        //$users = User::create($request->except('profile'));

        if ($request->profile == '') {
            $profile = null;
        } else {
            $image = $request->file('profile')->store('public/users_profile');
            $profile = Storage::url($image);
        }

        User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'dni' => $request->dni,
            'birthdate' => $request->birthdate,
            'license' => $request->license,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo_path' => $profile,
            'usertype_id' => $request->usertype_id,
            'zone_id' => $request->zone_id,
            'status' => 1,
        ]);


        return redirect()->route('admin.users.index')->with('success', 'Usuario registrado');
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

        $user = User::find($id);
        // $usertype = Usertype::pluck('name', 'id');
        $usertype = Usertype::where('id', '!=', 2)->pluck('name', 'id');
        $zone = Zone::pluck('name', 'id');
        return view('admin.users.edit', compact('user','usertype','zone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encuentra el usuario por ID
        $user = User::find($id);

        // Inicializa un array para almacenar los campos que se actualizarán
        $updateData = [
            'name' => $request->name,
            'lastname' => $request->lastname,
            'dni' => $request->dni,
            'birthdate' => $request->birthdate,
            'license' => $request->license,
            'address' => $request->address,  // Corrige 'adress' a 'address'
            'email' => $request->email,
            'usertype_id' => $request->usertype_id,
            'zone_id' => $request->zone_id,
        ];

        // Si el campo password no está vacío, agregarlo a los datos de actualización
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Si el campo profile no está vacío, maneja la carga de la imagen
        if (!empty($request->profile)) {
            // Verifica si el usuario ya tiene una imagen previa
            if ($user->profile_photo_path) {
                // Obtén la ruta completa de la imagen antigua y elimínala
                $oldImagePath = str_replace('/storage', 'public', $user->profile_photo_path);
                Storage::delete($oldImagePath);
            }

            // Carga la nueva imagen
            $image = $request->file('profile')->store('public/users_profile');
            $profile = Storage::url($image);
            $updateData['profile_photo_path'] = $profile;
        }

        // Actualiza el usuario con los datos correspondientes
        $user->update($updateData);

        // Redirecciona de nuevo a la página de índice de usuarios con un mensaje de éxito
        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);

        $user->update(['status' => 0]);

        // Redirigir o devolver una respuesta según sea necesario
        return redirect()->route('admin.users.index')->with('success', 'Usuario dado de baja');
    }
}
