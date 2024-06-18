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
            'id' => 'latitude_start'
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
            'id' => 'longitude_start'
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
            'id' => 'latitude_end'
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
            'id' => 'longitude_end'
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

@section('js')
    <script>
        function initializeMap() {
            var chiclayoCoords = { lat: -6.7737, lng: -79.8409 }; // Coordenadas de Chiclayo
            var mapOptions = {
                center: chiclayoCoords,
                zoom: 15 // Ajuste el nivel de zoom inicial para que esté más cerca
            };
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            map.addListener('click', function(event) {
                addMarker(event.latLng, map);
            });

            function addMarker(location, map) {
                new google.maps.Marker({
                    position: location,
                    map: map
                });
                if (!$('#latitude_start').val()) {
                    $('#latitude_start').val(location.lat());
                    $('#longitude_start').val(location.lng());
                } else {
                    $('#latitude_end').val(location.lat());
                    $('#longitude_end').val(location.lng());
                }
            }
        }

        window.onload = initializeMap;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initializeMap" async defer></script>
@stop

