{!! Form::model($maintenance, ['route' => ['admin.maintenances.update', $maintenance->id], 'method' => 'put', 'files' => true]) !!}
@include('admin.maintenances.partials.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
{!! Form::close() !!}