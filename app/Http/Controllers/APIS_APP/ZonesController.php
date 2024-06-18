<?php

namespace App\Http\Controllers\APIS_APP;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Routezone;
use App\Models\Zone;
use App\Models\Zonecoord;
use Illuminate\Http\Request;

class ZonesController extends Controller
{
    //solicitar una api_key para obtener listado de zonas
    public function __construct()
    {
        $this->middleware('api.key')->only('listZones');
    }

    //Obtener listado de zonas (para registro)
    public function listZones(Request $request)
    {
        try {
            $zones = Zone::all();
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Petición exitosa',
                'data' => $zones
            ], 200);
        } catch (\Exception $e) {
            // Manejar cualquier otra excepción
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function coordZoneUser(Request $request)
    {
        try {
            $user = $request->user(); // Obtenemos al usuario autenticado

            // Verificar si el usuario tiene una zona asignada
            if (!$user->zone_id) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'El usuario no tiene una zona asignada'
                ], 400);
            }

            // Obtener la zona del usuario
            $zone = Zone::find($user->zone_id);

            if (!$zone) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Zona no encontrada'
                ], 404);
            }

            // Obtener las coordenadas de la zona
            $zoneCoords = Zonecoord::where('zone_id', $zone->id)->get(['id', 'latitude', 'longitude']);

            if ($zoneCoords->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Coordenadas de la zona no encontradas'
                ], 404);
            }

            // Devolver la información de la zona y las coordenadas
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Coordenadas y datos de la zona obtenidos exitosamente',
                'data' => [
                    'zone' => [
                        'name' => $zone->name,
                        'description' => $zone->description,
                    ],
                    'coordinates' => $zoneCoords
                ]
            ], 200);
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function routeZoneUser(Request $request)
    {
        try {
            $user = $request->user(); // Obtenemos al usuario autenticado

            // Verificar si el usuario tiene una zona asignada
            if (!$user->zone_id) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'El usuario no tiene una zona asignada'
                ], 400);
            }

            // Obtener la zona del usuario
            $zone = Zone::find($user->zone_id);

            if (!$zone) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Zona no encontrada'
                ], 404);
            }

            // Obtener las rutas de la zona
            $routeZone = Routezone::where('zone_id', $zone->id)->get(['id', 'route_id', 'zone_id']);


            if ($routeZone->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Rutas de la zona no encontradas'
                ], 404);
            }

            // Array para almacenar la información de todas las rutas
            $rutas = [];

            // Iterar sobre la colección para acceder a cada route_id
            foreach ($routeZone as $rz) {
                // Obtener el route_id de cada Routezone
                $routeId = $rz->route_id;

                // Consultar la información de la ruta en la tabla Route
                $route = Route::where('id', $routeId)->first(['id', 'name', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status']);

                // Verificar si la ruta existe y agregarla al array
                if ($route) {
                    $rutas[] = [
                        'id' => $route->id,
                        'name' => $route->name,
                        'latitude_start' => $route->latitude_start,
                        'longitude_start' => $route->longitude_start,
                        'latitude_end' => $route->latitude_end,
                        'longitude_end' => $route->longitude_end,
                        'status' => $route->status
                    ];
                }
            }

            // Devolver la información de la zona y las coordenadas
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Coordenadas y datos de la zona obtenidos exitosamente',
                'data' => [
                    'rutas_zona' => $routeZone,
                    'rutas' => $rutas
                ]
            ], 200);
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
