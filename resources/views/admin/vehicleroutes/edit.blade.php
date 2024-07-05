{!! Form::model($vehicleroute, ['route' => ['admin.vehicleroutes.update', $vehicleroute->id], 'method' => 'put', 'files' => true]) !!}

@include('admin.vehicleroutes.partials.form')

<!-- Campos ocultos para filtros -->
<input type="hidden" name="date_range" id="hidden_date_range" value="{{ session('date_range') }}">
<input type="hidden" name="time_picker" id="hidden_time_picker" value="{{ session('time_picker') }}">
<input type="hidden" name="vehicle_filter" id="hidden_vehicle_filter" value="{{ session('vehicle_filter') }}">
<input type="hidden" name="route_filter" id="hidden_route_filter" value="{{ session('route_filter') }}">

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
<button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>

{!! Form::close() !!}
