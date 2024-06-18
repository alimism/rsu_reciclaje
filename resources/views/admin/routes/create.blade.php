{!! Form::open(['route' => 'admin.routes.store', 'files' => true]) !!}

@include('admin.routes.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>

{!! Form::close() !!}
