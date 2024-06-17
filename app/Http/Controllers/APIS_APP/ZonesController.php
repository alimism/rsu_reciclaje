<?php

namespace App\Http\Controllers\APIS_APP;

use App\Http\Controllers\Controller;
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


}
