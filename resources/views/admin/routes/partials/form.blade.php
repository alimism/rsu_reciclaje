<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre de la ruta',
        'required',
    ]) !!}
</div>

<div id="map" style="height: 400px; width: 100%;"></div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('latitude_start', 'Latitud de Inicio') !!}
        {!! Form::number('latitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud de inicio',
            'required',
            'step' => 'any',
            'readonly' => true,
            'id' => 'latitude_start',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude_start', 'Longitud de Inicio') !!}
        {!! Form::number('longitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud de inicio',
            'required',
            'step' => 'any',
            'readonly' => true,
            'id' => 'longitude_start',
        ]) !!}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('latitude_end', 'Latitud de Fin') !!}
        {!! Form::number('latitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud de fin',
            'required',
            'step' => 'any',
            'readonly' => true,
            'id' => 'latitude_end',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude_end', 'Longitud de Fin') !!}
        {!! Form::number('longitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud de fin',
            'required',
            'step' => 'any',
            'readonly' => true,
            'id' => 'longitude_end',
        ]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('status', 'Estado') !!}
    <div class="form-check">
        {!! Form::checkbox('status', 1, isset($route) ? $route->status : true, [
            'class' => 'form-check-input',
            'id' => 'statusCheckbox',
        ]) !!}
        <label class="form-check-label" for="statusCheckbox">Activo</label>
    </div>
</div>


<script>
    var map, startMarker, endMarker;
    var markerCount = 0; // Counter for markers

    function initializeMap() {
        var chiclayoCoords = {
            lat: -6.7737,
            lng: -79.8409
        }; // Coordenadas de Chiclayo
        var mapOptions = {
            center: chiclayoCoords,
            zoom: 15 // Ajuste el nivel de zoom inicial para que esté más cerca
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // Añadir marcadores si las coordenadas ya están definidas
        if ($('#latitude_start').val() && $('#longitude_start').val()) {
            var startPoint = {
                lat: parseFloat($('#latitude_start').val()),
                lng: parseFloat($('#longitude_start').val())
            };

            startMarker = new google.maps.Marker({
                position: startPoint,
                map: map,
                title: 'Punto de Inicio',
                label: 'A',
                draggable: true
            });
            startMarker.addListener('dragend', function(event) {
                updateMarkerPosition('start', event.latLng);
            });
            map.setCenter(startPoint); // Centrar el mapa en el punto de inicio
            markerCount++;
        }

        if ($('#latitude_end').val() && $('#longitude_end').val()) {
            var endPoint = {
                lat: parseFloat($('#latitude_end').val()),
                lng: parseFloat($('#longitude_end').val())
            };
            endMarker = new google.maps.Marker({
                position: endPoint,
                map: map,
                title: 'Punto de Fin',
                label: 'B',
                draggable: true
            });
            endMarker.addListener('dragend', function(event) {
                updateMarkerPosition('end', event.latLng);
            });
            markerCount++;
        }

        map.addListener('click', function(event) {
            addMarker(event.latLng);
        });
    }

    function addMarker(location) {
        if (markerCount < 2) {
            if (!startMarker) {
                $('#latitude_start').val(location.lat());
                $('#longitude_start').val(location.lng());
                startMarker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'Punto de Inicio',
                    label: 'A',
                    draggable: true
                });
                startMarker.addListener('dragend', function(event) {
                    updateMarkerPosition('start', event.latLng);
                });
            } else if (!endMarker) {
                $('#latitude_end').val(location.lat());
                $('#longitude_end').val(location.lng());
                endMarker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'Punto de Fin',
                    label: 'B',
                    draggable: true
                });
                endMarker.addListener('dragend', function(event) {
                    updateMarkerPosition('end', event.latLng);
                });
            }
            markerCount++;
        } else {
            Swal.fire({
                title: 'Límite de marcadores alcanzado',
                text: 'Solo se permiten dos marcadores: uno de inicio y uno de fin.',
                icon: 'warning',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: "#3085d6",

            });
        }
    }

    function updateMarkerPosition(type, location) {
        if (type === 'start') {
            $('#latitude_start').val(location.lat());
            $('#longitude_start').val(location.lng());
        } else if (type === 'end') {
            $('#latitude_end').val(location.lat());
            $('#longitude_end').val(location.lng());
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeMap();
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initializeMap" async
    defer></script>
