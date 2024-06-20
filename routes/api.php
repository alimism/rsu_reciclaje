<?php

use App\Http\Controllers\APIS_APP\AuthController;
use App\Http\Controllers\APIS_APP\UserController;
use App\Http\Controllers\APIS_APP\ZonesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/* RUTAS API DE AUTENTICACION */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');//proteger ruta logout

/*Zonas*/
// Route::get('listzones', [ZonesController::class, 'listZones']);
Route::get('listzones', [ZonesController::class, 'listZones'])->middleware('api.key'); // Zonas protegidas
Route::post('coordzonesuser', [ZonesController::class, 'coordZoneUser'])->middleware('auth:sanctum');//proteger ruta coordenadas zona
Route::post('routezonesuser', [ZonesController::class, 'routeZoneUser'])->middleware('auth:sanctum');//proteger ruta rutas de zona


/*Usuario*/
Route::post('updateProfile', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');//proteger ruta udpateprofile
Route::post('deleteUser', [UserController::class, 'deleteUser'])->middleware('auth:sanctum');//proteger ruta udpateprofile

