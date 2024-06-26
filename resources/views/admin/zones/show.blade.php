@extends('layouts.app')

@section('title', 'Asignación de Coordenadas')

@section('content')
    <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Regresar</a>
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">Perimetro de la Zona
            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i> Agregar coordenada</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 d-flex flex-column">
                    <div class="card flex-fill">
                        <div class="card-header">Datos de la Zona</div>
                        <div class="card-body">
                            <label for="">Zona:</label>
                            {{ $zone->name }}<br>
                            <label for="">Area:</label>
                            {{ $zone->area }}<br>
                            <label for="">Descripción:</label>
                            {{ $zone->description }}
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">Listado de Coordenadas</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>LATITUD</th>
                                        <th>LONGITUD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($zonecoords as $zonecoord)
                                        <tr>
                                            <td>{{ $zonecoord->id }}</td>
                                            <td>{{ $zonecoord->latitude }}</td>
                                            <td>{{ $zonecoord->longitude }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card col-12 p-2" style="min-height: 400px">
                <div id="mapShow" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Coordenadas de Zona</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        var mapInitialized = false;

        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json"
                }
            });

            $('#btnNuevo').click(function() {
                var id = {{ $zone->id }};
                $.ajax({
                    url: "{{ route('admin.zonecoords.edit', ['zonecoord' => '__id__']) }}".replace('__id__', id),
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    }
                });
            });

            $('#exampleModal').on('shown.bs.modal', function () {
                if (!mapInitialized) {
                    initMapModal();
                    mapInitialized = true;
                }
            });

            $('#exampleModal').on('hidden.bs.modal', function () {
                $('#mapShow').html('');
                initMap();
            });

            initMap();
        });

        function initMap() {
            var perimeterCoords = {!! $zonecoords->map(function ($coord) {
                return ['lat' => $coord->latitude, 'lng' => $coord->longitude];
            }) !!};

            if (perimeterCoords.length === 0) {
                $('#mapShow').text('No hay coordenadas definidas.');
                return;
            }

            function displayMap(lat, lng, perimeterCoords) {
                var mapOptions = {
                    center: { lat: lat, lng: lng },
                    zoom: 18
                };

                var map = new google.maps.Map(document.getElementById('mapShow'), mapOptions);

                if (perimeterCoords.length > 0) {
                    var perimeterPolygon = new google.maps.Polygon({
                        paths: perimeterCoords,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35
                    });

                    perimeterPolygon.setMap(map);

                    var bounds = new google.maps.LatLngBounds();
                    perimeterCoords.forEach(function(coord) {
                        bounds.extend(coord);
                    });

                    map.fitBounds(bounds);

                    google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                        this.setZoom(16); // Ajusta el nivel de zoom aquí
                    });

                    var centro = bounds.getCenter();
                    map.panTo(centro);
                }
            }

            displayMap(perimeterCoords[0].lat, perimeterCoords[0].lng, perimeterCoords);
        }

        function initMapModal() {
            // Inicializa el mapa en el modal de la misma manera
        }

        window.initMap = initMap;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Proceso Exitoso",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Error de Proceso",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif
@stop
