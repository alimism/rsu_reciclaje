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
                                                <button type="submit" class="btn btn-success"><i
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
                                        <td>{{ $zone->id }}</td>
                                        <td>{{ $zone->name }}</td>
                                        <td>
                                            <form
                                                action="{{ route('admin.routes.unassignZone', ['route' => $route->id, 'zone' => $zone->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
@endpush
