@extends('layouts.app')

@section('title', 'Asignación de Zonas')

@section('content')
    <div class="container">
        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i>
            Regresar</a>

        <div class="card mt-3">
            <div class="card-header">
                <h3>Asignar Zonas a la Ruta: {{ $route->name }}</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Zonas Disponibles</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($availableZones as $zone)
                                    <tr>
                                        <td>{{ $zone->id }}</td>
                                        <td>{{ $zone->name }}</td>
                                        <td>
                                            <form action="{{ route('admin.routes.assignZone', $route->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                                                <button type="submit" class="btn btn-success" onclick="updateRoute()"><i
                                                        class="fas fa-plus"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h4>Zonas Asignadas</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedZones as $zone)
                                    <tr>
                                        <td>{{ $zone['id'] }}</td>
                                        <td>{{ $zone['name'] }}</td>
                                        <td>
                                            <form
                                                action="{{ route('admin.routes.unassignZone', ['route' => $route->id, 'zone' => $zone['id']]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="updateRoute()"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="map" style="height: 400px; width: 100%;"></div>
                <div id="directionsPanel" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        var map, directionsService, directionsRenderer;

        function initializeMap() {
            var mapOptions = {
                center: {
                    lat: -6.7744,
                    lng: -79.8416
                }, // Centrar el mapa en Chiclayo
                zoom: 15
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
            directionsRenderer.setPanel(document.getElementById('directionsPanel'));

            loadRoute();
        }

        function loadRoute() {
            var startPoint = {
                lat: {{ $route->latitude_start }},
                lng: {{ $route->longitude_start }}
            };
            var endPoint = {
                lat: {{ $route->latitude_end }},
                lng: {{ $route->longitude_end }}
            };

            var zones = @json($assignedZones);
            var waypoints = [];

            zones.forEach(function(zone) {
                if (zone.coords && zone.coords.length > 0) {
                    var zoneCoords = zone.coords.map(function(coord) {
                        return {
                            lat: parseFloat(coord.latitude),
                            lng: parseFloat(coord.longitude)
                        };
                    });

                    var gridPoints = generateGridPoints(zoneCoords,
                        0.001); // Ajuste el valor del paso para mayor precisión

                    gridPoints.forEach(function(point) {
                        waypoints.push({
                            location: new google.maps.LatLng(point.lat, point.lng),
                            stopover: true
                        });
                    });

                    var zonePolygon = new google.maps.Polygon({
                        paths: zoneCoords,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35
                    });

                    zonePolygon.setMap(map);
                }
            });

            // Dividir en múltiples solicitudes si hay más de 25 waypoints
            if (waypoints.length > 25) {
                let waypointChunks = [];
                for (let i = 0; i < waypoints.length; i += 23) {
                    waypointChunks.push(waypoints.slice(i, i + 23));
                }

                let requests = waypointChunks.map((chunk, index) => {
                    return {
                        origin: index === 0 ? startPoint : chunk[0].location,
                        destination: index === waypointChunks.length - 1 ? endPoint : chunk[chunk.length - 1]
                            .location,
                        waypoints: chunk,
                        travelMode: 'DRIVING'
                    };
                });

                executeRouteRequests(requests, 0);
            } else {
                var request = {
                    origin: startPoint,
                    destination: endPoint,
                    waypoints: waypoints,
                    travelMode: 'DRIVING'
                };

                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error('Error generating route: ' + status);
                    }
                });
            }
        }

        function executeRouteRequests(requests, index) {
            if (index >= requests.length) return;

            directionsService.route(requests[index], function(result, status) {
                if (status == 'OK') {
                    if (index === 0) {
                        directionsRenderer.setDirections(result);
                    } else {
                        var currentDirections = directionsRenderer.getDirections();
                        var route = result.routes[0];

                        route.legs.forEach(function(leg) {
                            currentDirections.routes[0].legs.push(leg);
                        });

                        directionsRenderer.setDirections(currentDirections);
                    }
                    executeRouteRequests(requests, index + 1);
                } else {
                    console.error('Error generating route: ' + status);
                }
            });
        }

        function generateGridPoints(zoneCoords, step) {
            let minLat = Math.min(...zoneCoords.map(coord => coord.lat));
            let maxLat = Math.max(...zoneCoords.map(coord => coord.lat));
            let minLng = Math.min(...zoneCoords.map(coord => coord.lng));
            let maxLng = Math.max(...zoneCoords.map(coord => coord.lng));

            let points = [];
            let count = 0;

            for (let lat = minLat; lat <= maxLat; lat += step) {
                for (let lng = minLng; lng <= maxLng; lng += step) {
                    if (count >= 25) break; // Limitar a 25 puntos para evitar el error
                    let point = {
                        lat: lat,
                        lng: lng
                    };
                    if (google.maps.geometry.poly.containsLocation(new google.maps.LatLng(lat, lng), new google.maps
                            .Polygon({
                                paths: zoneCoords
                            }))) {
                        points.push(point);
                        count++;
                    }
                }
            }

            return points;
        }


        function updateRoute() {
            setTimeout(function() {
                loadRoute();
            }, 1000); // Esperar un segundo para asegurar que la zona se asigne/desasigne
        }

        window.onload = initializeMap;
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry&callback=initializeMap">
    </script>
@endpush
