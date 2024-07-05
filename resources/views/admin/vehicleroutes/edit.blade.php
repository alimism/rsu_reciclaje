{!! Form::model($vehicleroute, ['route' => ['admin.vehicleroutes.update', $vehicleroute->id], 'method' => 'put', 'files' => true]) !!}

@include('admin.vehicleroutes.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
<button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>

{!! Form::close() !!}
