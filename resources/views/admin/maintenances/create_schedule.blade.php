{!! Form::open(['route' => ['admin.maintenances.storeSchedule', $maintenance->id], 'files' => true]) !!}
@include('admin.maintenances.partials.form_schedule')
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
{!! Form::close() !!}
