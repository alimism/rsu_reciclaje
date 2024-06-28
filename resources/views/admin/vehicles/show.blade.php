@extends('layouts.app')

@section('title', 'Asignación de Personal')

@section('content')
    <div class="container">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i>
            Regresar</a>

        <div class="card mt-3">
            <div class="card-header">
                <h3>Asignar Ocupantes al Vehículo: {{ $vehicle->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id="vehicle-container" style="text-align: center;" class="col-8 d-flex justify-content-center">
                        {!! file_get_contents(public_path('vehicle_top_view.svg')) !!}
                    </div>

                    <div class="col-4">
                        <h4>Ocupantes Asignados:</h4>
                        <ul id="assigned-occupants" class="list-group list-group-flush">
                            <h5 id="conductor-header" style="display: none;">Conductor:</h5>
                            <li id="conductor-details" class="list-group-item"></li>
                            <h5 id="recolectores-header" style="display: none;">Recolectores:</h5>
                            <li id="recolectores-details" class="list-group-item"></li>
                            <h5 id="extra-occupants-header" style="display: none;">Ocupantes Adicionales:</h5>
                            <li id="extra-occupants-details" class="list-group-item"></li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('admin.vehicles.assignOccupants', $vehicle->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="conductor">Seleccionar Conductor:</label>
                        @if ($conductores->isEmpty())
                            <div class="alert alert-info">No hay conductores registrados.</div>
                        @else
                            <select name="conductor" id="conductor" class="form-control select2"
                                data-placeholder="Seleccione un conductor">
                                <option value=""></option>
                                @foreach ($conductores as $conductor)
                                    <option value="{{ $conductor->id }}" data-name="{{ $conductor->name }}"
                                        data-usertype="Conductor" @if ($vehicle->occupants->pluck('user_id')->contains($conductor->id)) selected @endif>
                                        {{ $conductor->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="recolectores">Seleccionar Recolectores:</label>
                        @if ($recolectores->isEmpty())
                            <div class="alert alert-info">No hay recolectores registrados.</div>
                        @else
                            <select name="recolectores[]" id="recolectores" class="form-control select2" multiple
                                data-placeholder="Seleccione los recolectores">
                                @foreach ($recolectores as $recolector)
                                    <option value="{{ $recolector->id }}" data-name="{{ $recolector->name }}"
                                        data-usertype="Recolector" @if ($vehicle->occupants->pluck('user_id')->contains($recolector->id)) selected @endif>
                                        {{ $recolector->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Asignar</button>
                </form>
            </div>
        </div>
    </div>
@stop
@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 con tema bootstrap-5 y permitir limpieza
            $('.select2').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });


            // Función para calcular un color secundario (más oscuro)
            function calculateSecondaryColor(hex) {
                let r = parseInt(hex.slice(1, 3), 16);
                let g = parseInt(hex.slice(3, 5), 16);
                let b = parseInt(hex.slice(5, 7), 16);
                // Hacer el color más oscuro reduciendo la intensidad
                r = Math.max(0, r - 50);
                g = Math.max(0, g - 50);
                b = Math.max(0, b - 50);
                return '#' + r.toString(16).padStart(2, '0') + g.toString(16).padStart(2, '0') + b.toString(16)
                    .padStart(2, '0');
            }

            // Asignar colores al SVG
            function assignColorsToSVG(primaryColor, secondaryColor) {
                $('.primary-fill-color').attr('fill', primaryColor);
                $('.secondary-fill-color').attr('fill', secondaryColor);
            }

            // Obtener el color primario desde el controlador
            var primaryColor = '{{ $primaryColor }}';
            var secondaryColor = calculateSecondaryColor(primaryColor);

            // Asignar los colores al SVG
            assignColorsToSVG(primaryColor, secondaryColor);

            // Colores para los asientos de los recolectores
            var seatColors = ['lightblue', 'lightcoral', 'lightgreen', 'lightpink'];

            // Inicializar asientos con los datos existentes
            initializeSeats();
            updateAssignedOccupants();
            updateSeatVisibility();

            // Manejar cambios en el select de conductor
            $('#conductor').on('change', function() {
                resetConductorSeats(); // Resetea los asientos del conductor
                updateAssignedOccupants(); // Actualiza la lista de ocupantes asignados
                var selected = $(this).find('option:selected').val(); // Obtiene el valor seleccionado
                if (selected) {
                    $('#seat1').attr('fill', 'lightgreen'); // Asigna color al asiento del conductor
                }
            });

            // Manejar cambios en el select de recolectores
            $('#recolectores').on('change', function() {
                resetRecolectorSeats(); // Resetea los asientos de los recolectores
                updateAssignedOccupants(); // Actualiza la lista de ocupantes asignados
                var selectedOptions = $(this).find('option:selected'); // Obtiene las opciones seleccionadas
                selectedOptions.each(function(index, option) {
                    if (index < 9) {
                        var seat = $('#seat' + (index +
                            2)); // Asigna colores a los asientos de recolectores
                        seat.attr('fill', seatColors[index % seatColors.length]);
                    }
                });
            });

            // Resetea el color del asiento del conductor
            function resetConductorSeats() {
                $('#seat1').attr('fill', 'white');
            }

            // Resetea los colores de los asientos de los recolectores
            function resetRecolectorSeats() {
                for (var i = 2; i <= 10; i++) {
                    $('#seat' + i).attr('fill', 'white');
                }
            }

            // Actualiza la visibilidad de los asientos según la capacidad del vehículo
            function updateSeatVisibility() {
                var capacity = {{ $capacity }};
                for (var i = 2; i <= 10; i++) {
                    if (i <= capacity) {
                        $('#seat' + i).show(); // Muestra los asientos dentro de la capacidad
                    } else {
                        $('#seat' + i).hide(); // Oculta los asientos fuera de la capacidad
                    }
                }
            }

            // Inicializa los asientos con datos existentes
            function initializeSeats() {
                var extraOccupants = [];
                var seatIndex = 1;
                @foreach ($vehicle->occupants as $occupant)

                    if ({{ $occupant->usertype_id }} == 3) {
                        $('#seat1').attr('fill', 'lightgreen'); // Asigna color al asiento del conductor
                    } else if ({{ $occupant->usertype_id }} == 4) {
                        if (seatIndex < {{ $capacity }}) {
                            $('#seat' + (seatIndex + 1)).attr('fill', seatColors[seatIndex % seatColors
                                .length]); // Asigna color a los asientos de recolectores
                            seatIndex++;
                        } else {
                            extraOccupants.push('{{ $occupant->name }}'); // Añade ocupantes adicionales
                        }
                    }
                @endforeach
                updateExtraOccupants(extraOccupants); // Actualiza la lista de ocupantes adicionales
            }

            // Actualiza la lista de ocupantes asignados
            function updateAssignedOccupants() {
                var conductor = $('#conductor').find('option:selected'); // Obtiene el conductor seleccionado
                var recolectores = $('#recolectores').find(
                    'option:selected'); // Obtiene los recolectores seleccionados
                var extraOccupants = [];

                $('#conductor-details').empty(); // Limpia los detalles del conductor
                if (conductor.length > 0 && conductor.val() !== "") {
                    $('#conductor-header').show(); // Muestra el encabezado del conductor
                    $('#conductor-details').text(conductor.data('name') + ' (' + conductor.data('usertype') +
                        ')'); // Muestra el nombre y tipo del conductor
                } else {
                    $('#conductor-header')
                        .hide(); // Oculta el encabezado del conductor si no hay conductor seleccionado
                    $('#conductor-details').text('');
                }

                $('#recolectores-details').empty(); // Limpia los detalles de los recolectores
                if (recolectores.length > 0) {
                    $('#recolectores-header').show(); // Muestra el encabezado de recolectores
                    recolectores.each(function(index) {
                        if (index < 9) {
                            $('#recolectores-details').append('<li>' + $(this).data('name') + ' (' + $(this)
                                .data('usertype') + ')</li>'); // Añade los recolectores a la lista
                        } else {
                            extraOccupants.push($(this).data('name') + ' (' + $(this).data('usertype') +
                                ')'); // Añade recolectores adicionales
                        }
                    });
                } else {
                    $('#recolectores-header')
                        .hide(); // Oculta el encabezado de recolectores si no hay recolectores seleccionados
                }
                updateExtraOccupants(extraOccupants); // Actualiza la lista de ocupantes adicionales
            }

            // Actualiza la lista de ocupantes adicionales
            function updateExtraOccupants(extraOccupants) {
                $('#extra-occupants-details').empty(); // Limpia los detalles de ocupantes adicionales
                if (extraOccupants.length > 0) {
                    $('#extra-occupants-header').show(); // Muestra el encabezado de ocupantes adicionales
                    extraOccupants.forEach(function(occupant) {
                        $('#extra-occupants-details').append('<li>' + occupant +
                            '</li>'); // Añade ocupantes adicionales a la lista
                    });
                } else {
                    $('#extra-occupants-header')
                        .hide(); // Oculta el encabezado de ocupantes adicionales si no hay ocupantes adicionales
                }
            }
        });
    </script>
@endpush
