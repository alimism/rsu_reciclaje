<div class="form-row">
    <!-- Campo oculto para almacenar el ID de la zona -->
    {!! Form::hidden('zone_id', $zone->id, null) !!}
    
    <div class="form-group col-12">
        <label>Coordenadas</label>
        <div id="coords-container" style="max-height: 400px; overflow-y: auto;">
            <!-- Las coordenadas se agregarán dinámicamente aquí -->
        </div>
    </div>
    
    <div class="form-group col-12 d-flex align-items-end">
        <!-- Botón para agregar una nueva coordenada -->
        <a id="add-coord" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
    </div>
</div>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#" id="prev-page">Anterior</a></li>
        <li class="page-item"><a class="page-link" href="#" id="next-page">Siguiente</a></li>
    </ul>
</nav>

<div id="mapModal" style="height: 400px; width:100%; border: 1px solid black;"></div><br>

<script>
    var coordsContainer = document.getElementById('coords-container');
    var map, perimeterPolygon;
    var markers = [];
    var currentPage = 1;
    var itemsPerPage = 5; // Número de filas por página

    // Agregar evento al botón de agregar coordenada
    document.getElementById('add-coord').addEventListener('click', function() {
        var center = map.getCenter();
        addCoordRow(center.lat(), center.lng()); // Usar coordenadas actuales del centro del mapa
        renderPage(currentPage); // Renderizar la página actual después de agregar la coordenada
    });

    // Función para agregar una fila de coordenadas
    function addCoordRow(lat, lng) {
        var coordRow = document.createElement('div');
        coordRow.classList.add('coord-row', 'form-row', 'mb-2');
        coordRow.innerHTML = `
            <div class="form-group col-5">
                <input type="text" name="latitude[]" class="form-control" placeholder="Ingrese la latitud" value="${lat}" required>
            </div>
            <div class="form-group col-5">
                <input type="text" name="longitude[]" class="form-control" placeholder="Ingrese la longitud" value="${lng}" required>
            </div>
            <div class="form-group col-2 d-flex align-items-end">
                <!-- Botón para eliminar una coordenada -->
                <a class="btn btn-danger btn-sm remove-coord"><i class="fas fa-trash"></i></a>
            </div>
        `;
        coordsContainer.appendChild(coordRow);

        var marker = addMarker(lat, lng); // Agregar marcador en el mapa
        marker.coordRow = coordRow; // Asociar el marcador con la fila de coordenadas

        // Agregar evento al botón de eliminar coordenada
        coordRow.querySelector('.remove-coord').addEventListener('click', function() {
            removeCoordRow(coordRow, marker); // Eliminar fila de coordenada y marcador asociado
        });
    }

    // Función para eliminar una fila de coordenadas y su marcador asociado
    function removeCoordRow(coordRow, marker) {
        coordRow.remove(); // Eliminar fila del DOM
        marker.setMap(null); // Eliminar marcador del mapa
        markers = markers.filter(m => m !== marker); // Remover marcador de la lista
        updatePerimeter(); // Actualizar perímetro
        renderPage(currentPage); // Renderizar la página actual
    }

    // Función para agregar un marcador al mapa
    function addMarker(lat, lng) {
        var marker = new google.maps.Marker({
            position: {
                lat: lat,
                lng: lng
            },
            map: map,
            draggable: true, // Hacer el marcador arrastrable
        });

        markers.push(marker); // Agregar marcador a la lista de marcadores

        // Evento para actualizar coordenadas al arrastrar el marcador
        marker.addListener('dragend', function(event) {
            var latLng = event.latLng; // Obtener nueva posición del marcador
            updateCoordInputs(marker, latLng.lat(), latLng.lng()); // Actualizar inputs de coordenadas
            updatePerimeter(); // Actualizar perímetro
        });

        updatePerimeter(); // Actualizar perímetro después de agregar el marcador

        return marker; // Retornar el marcador creado
    }

    // Función para actualizar los inputs de coordenadas al mover un marcador
    function updateCoordInputs(marker, lat, lng) {
        var index = markers.indexOf(marker); // Obtener índice del marcador
        var coordRows = document.querySelectorAll('.coord-row'); // Obtener todas las filas de coordenadas
        var coordRow = coordRows[index]; // Obtener la fila correspondiente al marcador
        if (coordRow) {
            coordRow.querySelector('input[name="latitude[]"]').value = lat; // Actualizar valor de latitud
            coordRow.querySelector('input[name="longitude[]"]').value = lng; // Actualizar valor de longitud
        }
    }

    // Función para actualizar el perímetro dibujado en el mapa
    function updatePerimeter() {
        var perimeterCoords = markers.map(marker => marker.getPosition().toJSON()); // Obtener coordenadas de todos los marcadores

        if (perimeterPolygon) {
            perimeterPolygon.setMap(null); // Eliminar el perímetro anterior del mapa
        }

        if (perimeterCoords.length > 0) {
            perimeterPolygon = new google.maps.Polygon({
                paths: perimeterCoords, // Establecer las coordenadas del perímetro
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });

            perimeterPolygon.setMap(map); // Agregar el nuevo perímetro al mapa
        }
    }

    // Función para paginar las filas de coordenadas
    function renderPage(page) {
        var coordRows = document.querySelectorAll('.coord-row'); // Obtener todas las filas de coordenadas
        var totalItems = coordRows.length; // Número total de filas
        var totalPages = Math.ceil(totalItems / itemsPerPage); // Calcular el número total de páginas

        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        // Mostrar u ocultar filas según la página actual
        for (var i = 0; i < totalItems; i++) {
            coordRows[i].style.display = (i >= (page - 1) * itemsPerPage && i < page * itemsPerPage) ? 'flex' : 'none';
        }

        // Deshabilitar botones de navegación si se está en la primera o última página
        document.getElementById('prev-page').parentElement.classList.toggle('disabled', page == 1);
        document.getElementById('next-page').parentElement.classList.toggle('disabled', page == totalPages);
    }

    // Evento para cambiar a la página anterior
    document.getElementById('prev-page').addEventListener('click', function() {
        renderPage(--currentPage); // Renderizar la página anterior
    });

    // Evento para cambiar a la página siguiente
    document.getElementById('next-page').addEventListener('click', function() {
        renderPage(++currentPage); // Renderizar la página siguiente
    });

    // Función para inicializar el mapa en el modal
    function initMapModal() {
        var mapOptions = {
            center: {
                lat: -34.397,
                lng: 150.644
            }, // Coordenadas por defecto
            zoom: 18
        };

        map = new google.maps.Map(document.getElementById('mapModal'), mapOptions); // Crear el mapa

        // Obtener la ubicación actual del usuario
        navigator.geolocation.getCurrentPosition(function(position) {
            var currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map.setCenter(currentLocation); // Centrar el mapa en la ubicación actual
            map.setZoom(18); // Establecer el zoom del mapa
        }, function() {
            console.error('Error al obtener la ubicación actual');
        });

        // Limpiar contenedor de coordenadas
        coordsContainer.innerHTML = '';

        // Inicializar el mapa con las coordenadas existentes
        @foreach ($zone->coords as $coord)
            addCoordRow({{ $coord->latitude }}, {{ $coord->longitude }});
        @endforeach

        renderPage(currentPage); // Renderizar la primera página de coordenadas
    }

    // Hacer la función de inicialización del mapa disponible globalmente
    window.initMapModal = initMapModal;
</script>
