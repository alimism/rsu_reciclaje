<?php

namespace App\Http\Controllers\APIS_APP;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Routezone;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleOccupant;
use App\Models\Vehicleroute;
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

    public function routeZoneUser2(Request $request)
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

    // Ruta del día de hoy
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

            // Obtener la ruta de la zona
            $routeZone = Routezone::where('zone_id', $zone->id)->first(['id', 'route_id', 'zone_id']);

            if (!$routeZone) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Ruta de la zona no encontrada'
                ], 404);
            }

            // Consultar la información de la ruta en la tabla Route
            $route = Route::where('id', $routeZone->route_id)->first(['id', 'name', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status']);

            if (!$route) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Ruta no encontrada'
                ], 404);
            }

            // Consultar en la tabla Vehicleroutes
            $today = date('Y-m-d');
            $vehicleRoute = Vehicleroute::where('route_id', $route->id)
                ->where('routestatus_id', 1)
                ->whereDate('date_route', $today)
                ->first();

            if (!$vehicleRoute) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No hay rutas activas para el día de hoy'
                ], 404);
            }

            // Obtener el vehicle_id de la ruta activa
            $vehicle = Vehicle::where('id', $vehicleRoute->vehicle_id)->first(['id', 'name','code','plate']);

            if (!$vehicle) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Vehículo no encontrado'
                ], 404);
            }

            // Consultar la tabla vehicle_ocuppants para obtener el user_id y verificar el usertype_id
            $vehicleOccupant = VehicleOccupant::where('vehicle_id', $vehicle->id)
                ->where('usertype_id',3)
                ->where('status',1)
                ->first(['user_id']);

            if (!$vehicleOccupant) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Conductor del vehículo no encontrado'
                ], 404);
            }

            // Consultar la tabla User para obtener los datos del chofer
            $driver = User::where('id', $vehicleOccupant->user_id)->first(['id', 'name','lastname', 'DNI', 'license','email']);

            if (!$driver) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Datos del chofer no encontrados'
                ], 404);
            }

            // Devolver la información de la zona, la ruta, el vehículo y el chofer
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos obtenidos exitosamente',
                'data' => [
                    'ruta_zona' => $routeZone,
                    'ruta' => $route,
                    'vehicle_routes'=>$vehicleRoute,
                    'vehiculo' => $vehicle,
                    'chofer' => $driver
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

    //Programación de ruta
    public function programationRouteUser(Request $request)
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

            // Obtener la ruta de la zona
            $routeZone = Routezone::where('zone_id', $zone->id)->first(['id', 'route_id', 'zone_id']);

            if (!$routeZone) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se ha encontrado una ruta en su zona'
                ], 404);
            }

            // Consultar la información de la ruta en la tabla Route
            $route = Route::where('id', $routeZone->route_id)->first(['id', 'name', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status']);

            if (!$route) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Ruta no encontrada'
                ], 404);
            }

            // Obtener la fecha de hoy
            $today = date('Y-m-d');

            // Consultar todos los registros de Vehicleroutes donde routestatus_id sea 1
            $vehicleRoutes = Vehicleroute::where('route_id', $route->id)
                ->where('routestatus_id', 1)
                ->whereDate('date_route', '>=', $today)
                ->get();

            // if ($vehicleRoutes->isEmpty()) {
            //     return response()->json([
            //         'status' => 'error',
            //         'code' => 404,
            //         'message' => 'No hay programación para la ruta de su zona'
            //     ], 404);
            // }

            // Inicializar array para almacenar los vehículos y conductores agrupados
            $vehicleDriverData = [];

            // Iterar sobre los registros de Vehicleroutes
            foreach ($vehicleRoutes as $vehicleRoute) {
                // Obtener el vehicle_id de la ruta activa
                $vehicle = Vehicle::where('id', $vehicleRoute->vehicle_id)->first(['id', 'name', 'code', 'plate']);

                if ($vehicle) {
                    // Consultar la tabla vehicle_occupants para obtener el user_id y verificar el usertype_id
                    $vehicleOccupant = VehicleOccupant::where('vehicle_id', $vehicle->id)
                        ->where('usertype_id', 3)
                        ->where('status', 1)
                        ->first(['user_id']);

                    if ($vehicleOccupant) {
                        // Consultar la tabla User para obtener los datos del chofer
                        $driver = User::where('id', $vehicleOccupant->user_id)->first(['id', 'name', 'lastname', 'DNI', 'license', 'email']);

                        if ($driver) {
                            $vehicleDriverData[] = [
                                'vehicle_route' => $vehicleRoute,
                                'vehicle' => $vehicle,
                                'driver' => $driver
                            ];
                        }
                    }
                }
            }

            // Devolver la información de la zona, la ruta, los vehículos y los choferes
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos obtenidos exitosamente',
                'data' => [
                    'ruta_zona' => $routeZone,
                    'ruta' => $route,
                    'vehicle_driver_data' => $vehicleDriverData
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
