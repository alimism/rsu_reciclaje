{!! Form::open(['route' => 'admin.vehicleroutes.store']) !!}
@include('admin.vehicleroutes.partials.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
{!! Form::close() !!}
