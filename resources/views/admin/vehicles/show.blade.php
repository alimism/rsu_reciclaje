@extends('adminlte::page')

@section('title', 'Asignación de Personal')

@section('content')
    <div class="container">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Regresar</a>

        <div class="card mt-3">
            <div class="card-header">
                <h3>Asignar Ocupantes al Vehículo: {{ $vehicle->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id="vehicle-container" style="text-align: center;" class="col-8">
                        <svg width="150" height="550" viewBox="0 0 200 600" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(-90deg);">
                            <!-- Truck Body -->
                            <rect x="25" y="150" width="150" height="350" stroke="black" fill="slategrey" stroke-width="2" />
                            <!-- Cab -->
                            <rect x="25" y="50" width="150" height="100" stroke="black" fill="darkslategrey" stroke-width="2" />
                            <!-- Windows -->
                            <rect x="30" y="50" width="70" height="10" stroke="black" fill="ghostwhite" stroke-width="2" />
                            <rect x="100" y="50" width="70" height="10" stroke="black" fill="ghostwhite" stroke-width="2" />
                            <!-- Seats in Cab -->
                            <rect id="seat1" x="45" y="85" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <rect id="seat2" x="115" y="85" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <!-- Mirrors -->
                            <rect x="5" y="85" width="20" height="10" stroke="black" fill="darkslategrey" stroke-width="2" />
                            <rect x="175" y="85" width="20" height="10" stroke="black" fill="darkslategrey" stroke-width="2" />
                            <!-- Seats in Cargo Area -->
                            <rect id="seat3" x="45" y="300" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <rect id="seat4" x="115" y="300" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <rect id="seat5" x="45" y="400" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <rect id="seat6" x="115" y="400" width="40" height="40" stroke="black" fill="white" stroke-width="2" />
                            <!-- Base for Steering Wheel -->
                            <rect x="30" y="60" width="140" height="10" stroke="black" fill="darkslategrey" stroke-width="2" />
                            <!-- Steering Wheel -->
                            <circle cx="65" cy="72" r="8" stroke="black" fill="black" stroke-width="2" />
                            <circle cx="65" cy="72" r="6" stroke="black" fill="darkslategrey" stroke-width="2" />
                            <rect x="60" y="71" width="10" height="2" stroke="black" fill="none" stroke-width="2" />
                            <rect x="64" y="71" width="2" height="5" stroke="black" fill="none" stroke-width="2" />
                            <circle cx="65" cy="72.5" r="1.5" stroke="black" fill="ghostwhite" stroke-width="0" />
                        </svg>
                    </div>

                    <div class="col-4">
                        <h4>Ocupantes Asignados:</h4>
                        <ul id="assigned-occupants" class="list-group list-group-flush">
                            <h5 id="conductor-header" style="display: none;">Conductor:</h5>
                            <li id="conductor-details" class="list-group-item"></li>
                            <h5 id="recolectores-header" style="display: none;">Recolectores:</h5>
                            <li id="recolectores-details" class="list-group-item"></li>
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
                            <select name="conductor" id="conductor" class="form-control select2" data-placeholder="Seleccione un conductor">
                                <option value=""></option>
                                @foreach ($conductores as $conductor)
                                    <option value="{{ $conductor->id }}" data-name="{{ $conductor->name }}" data-usertype="Conductor"
                                        @if ($vehicle->occupants->where('usertype_id', 3)->pluck('user_id')->contains($conductor->id)) selected @endif>
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
                            <select name="recolectores[]" id="recolectores" class="form-control select2" multiple data-placeholder="Seleccione los recolectores">
                                @foreach ($recolectores as $recolector)
                                    <option value="{{ $recolector->id }}" data-name="{{ $recolector->name }}" data-usertype="Recolector"
                                        @if ($vehicle->occupants->where('usertype_id', 4)->pluck('user_id')->contains($recolector->id)) selected @endif>
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
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    placeholder: function() {
                        $(this).data('placeholder');
                    }
                });

                var seatColors = ['lightblue', 'lightcoral', 'lightgreen', 'lightpink'];

                // Initialize with existing data
                initializeSeats();
                updateAssignedOccupants();

                $('#conductor').on('change', function() {
                    resetConductorSeats();
                    updateAssignedOccupants();
                    var selected = $(this).find('option:selected').val();
                    $('#seat1').attr('fill', selected ? 'lightgreen' : 'white');
                });

                $('#recolectores').on('change', function() {
                    resetRecolectorSeats();
                    updateAssignedOccupants();
                    var selectedOptions = $(this).find('option:selected');
                    selectedOptions.each(function(index, option) {
                        var seat = $('#seat' + (index + 2));
                        seat.attr('fill', seatColors[index % seatColors.length]);
                    });
                });

                function resetConductorSeats() {
                    $('#seat1').attr('fill', 'white');
                }

                function resetRecolectorSeats() {
                    for (var i = 2; i <= 5; i++) {
                        $('#seat' + i).attr('fill', 'white');
                    }
                }

                function initializeSeats() {
                    @foreach ($vehicle->occupants as $occupant)
                        if ({{ $occupant->usertype_id }} == 3) {
                            $('#seat1').attr('fill', 'lightgreen');
                        } else if ({{ $occupant->usertype_id }} == 4) {
                            var seatIndex = $('#recolectores option[value="{{ $occupant->user_id }}"]').index();
                            $('#seat' + (seatIndex + 2)).attr('fill', seatColors[seatIndex % seatColors.length]);
                        }
                    @endforeach
                }

                function updateAssignedOccupants() {
                    var conductor = $('#conductor').find('option:selected');
                    var recolectores = $('#recolectores').find('option:selected');

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
                        recolectores.each(function() {
                            $('#recolectores-details').append('<li>' + $(this).data('name') + ' (' + $(this).data('usertype') + ')</li>');
                        });
                    } else {
                        $('#recolectores-header').hide();
                    }
                }
            });
        </script>
    @endpush
@stop
