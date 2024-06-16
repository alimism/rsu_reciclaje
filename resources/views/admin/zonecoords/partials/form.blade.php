<div class="form-row">
    {!! Form::hidden('zone_id', $zone->id, null) !!}
    <div class="form-group col-6">
        {!! Form::label('latitude', 'Latitud') !!}
        {!! Form::text('latitude', $zone->coords->first()->latitude ?? '', [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud',
            'readonly',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude', 'Longitud') !!}
        {!! Form::text('longitude', $zone->coords->first()->longitude ?? '', [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud',
            'readonly',
            'required',
        ]) !!}
    </div>
</div>
<div id="map" style="height: 400px; width:100%; border: 1px solid black;"></div><br>

<script>
    var latInput = document.getElementById('latitude');
    var lonInput = document.getElementById('longitude');

    function initMap() {
        var lat = parseFloat(latInput.value);
        var lng = parseFloat(lonInput.value);

        if (isNaN(lat) || isNaN(lng)) {
            navigator.geolocation.getCurrentPosition(function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude;
                latInput.value = lat;
                lonInput.value = lng;
                displayMap(lat, lng);
            });
        } else {
            displayMap(lat, lng);
        }
    }

    function displayMap(lat, lng) {
        var mapOptions = {
            center: { lat: lat, lng: lng },
            zoom: 18
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Coordenada',
            draggable: true,
        });

        var perimeterCoords = @json($zone->coords->map(function($coord) {
            return ['lat' => $coord->latitude, 'lng' => $coord->longitude];
        }));

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
            perimeterPolygon.getPath().forEach(function(coord) {
                bounds.extend(coord);
            });

            google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                this.setZoom(17); // Ajusta el nivel de zoom aquí
            });

            var centro = bounds.getCenter();
            map.panTo(centro);
        } else {
            console.error('No perimeter coordinates found');
        }

        google.maps.event.addListener(marker, 'dragend', function(event) {
            var latLng = event.latLng;
            latInput.value = latLng.lat();
            lonInput.value = latLng.lng();
        });
    }

    window.initMap = initMap;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>

