@extends('adminlte::page')

@section('title', 'Asignación de Personal')

@section('content')
    <div class="container">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Regresar
        </a>

        <div class="card mt-3">
            <div class="card-header">
                <h3>Asignar Ocupantes al Vehículo: {{ $vehicle->name }}</h3>
            </div>
            <div class="card-body">
                <h4>Ocupantes Asignados:</h4>
                <ul>
                    @foreach ($vehicle->occupants as $occupant)
                        <li>{{ $occupant->user->name }} ({{ $occupant->usertype->name }})</li>
                    @endforeach
                </ul>

                <form action="{{ route('admin.vehicles.assignOccupants', $vehicle->id) }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="conductor">Seleccionar Conductor:</label>
                            @if ($conductores->isEmpty())
                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i>
                                    No hay conductores registrados.
                                </div>
                            @else
                                <select name="conductor" id="conductor" class="form-control">
                                    <option value="">Seleccione un conductor</option>
                                    @foreach ($conductores as $conductor)
                                        <option value="{{ $conductor->id }}">{{ $conductor->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="form-group col-6">
                            <label for="recolectores">Seleccionar Recolectores:</label>
                            @if ($recolectores->isEmpty())
                                <div class="alert alert-info" role="alert"> 
                                    <i class="fas fa-info-circle"></i>
                                    No hay recolectores registrados.
                                </div>
                            @else
                                <select name="recolectores[]" id="recolectores" class="form-control" multiple>
                                    @foreach ($recolectores as $recolector)
                                        <option value="{{ $recolector->id }}">{{ $recolector->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Asignar</button>
                </form>
            </div>
        </div>
    </div>
@stop
