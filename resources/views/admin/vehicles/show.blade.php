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

    @push('js')
        <script>
            $(document).ready(function() {
                // Inicializar Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    placeholder: function() {
                        return $(this).data('placeholder');
                    }
                });

                // Colores para los recolectores
                var seatColors = ['lightblue', 'lightcoral', 'lightgreen', 'lightpink'];

                // Inicializar asientos con data existente, si ya hay asignaciones
                initializeSeats();
                updateAssignedOccupants();
                updateSeatVisibility();

                // Manejar select de conductor
                $('#conductor').on('change', function() {
                    resetConductorSeats();
                    updateAssignedOccupants();
                    var selected = $(this).find('option:selected').val();
                    if (selected) {
                        $('#seat1').attr('fill', 'lightgreen');
                    }
                });

                // Manejar select de recolectores
                $('#recolectores').on('change', function() {
                    resetRecolectorSeats();
                    updateAssignedOccupants();
                    var selectedOptions = $(this).find('option:selected');
                    selectedOptions.each(function(index, option) {
                        if (index < 9) {
                            var seat = $('#seat' + (index + 2));
                            seat.attr('fill', seatColors[index % seatColors.length]);
                        }
                    });
                });

                // Resetear asientos del conductor
                function resetConductorSeats() {
                    $('#seat1').attr('fill', 'white');
                }

                // Resetear asientos de los recolectores
                function resetRecolectorSeats() {
                    for (var i = 2; i <= 10; i++) {
                        $('#seat' + i).attr('fill', 'white');
                    }
                }

                // Actualizar la visibilidad de los asientos según la capacidad
                function updateSeatVisibility() {
                    var capacity = {{ $capacity }};
                    for (var i = 2; i <= 10; i++) {
                        if (i <= capacity) {
                            $('#seat' + i).show();
                        } else {
                            $('#seat' + i).hide();
                        }
                    }
                }

                function initializeSeats() {
                    var extraOccupants = [];
                    var seatIndex = 1;
                    @foreach ($vehicle->occupants as $occupant)

                        if ({{ $occupant->usertype_id }} == 3) {
                            $('#seat1').attr('fill', 'lightgreen');
                        } else if ({{ $occupant->usertype_id }} == 4) {
                            if (seatIndex < {{ $capacity }}) {
                                $('#seat' + (seatIndex + 1)).attr('fill', seatColors[seatIndex % seatColors.length]);
                                seatIndex++;
                            } else {
                                extraOccupants.push('{{ $occupant->name }}');
                            }
                        }
                    @endforeach
                    updateExtraOccupants(extraOccupants);
                }

                function updateAssignedOccupants() {
                    var conductor = $('#conductor').find('option:selected');
                    var recolectores = $('#recolectores').find('option:selected');
                    var extraOccupants = [];

                    $('#conductor-details').empty();
                    if (conductor.length > 0 && conductor.val() !== "") {
                        $('#conductor-header').show();
                        $('#conductor-details').text(conductor.data('name') + ' (' + conductor.data('usertype') + ')');
                    } else {
                        $('#conductor-header').hide();
                        $('#conductor-details').text('');
                    }

                    $('#recolectores-details').empty();
                    if (recolectores.length > 0) {
                        $('#recolectores-header').show();
                        recolectores.each(function(index) {
                            if (index < 9) {
                                $('#recolectores-details').append('<li>' + $(this).data('name') + ' (' + $(this)
                                    .data('usertype') + ')</li>');
                            } else {
                                extraOccupants.push($(this).data('name') + ' (' + $(this).data('usertype') +
                                    ')');
                            }
                        });
                    } else {
                        $('#recolectores-header').hide();
                    }
                    updateExtraOccupants(extraOccupants);
                }

                function updateExtraOccupants(extraOccupants) {
                    $('#extra-occupants-details').empty();
                    if (extraOccupants.length > 0) {
                        $('#extra-occupants-header').show();
                        extraOccupants.forEach(function(occupant) {
                            $('#extra-occupants-details').append('<li>' + occupant + '</li>');
                        });
                    } else {
                        $('#extra-occupants-header').hide();
                    }
                }
            });
        </script>
    @endpush
@stop
