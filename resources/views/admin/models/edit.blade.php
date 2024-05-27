{!! Form::model($model, ['route' => ['admin.models.update', $model->id], 'method' => 'put']) !!}
@include('admin.models.partials.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
<button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>
{!! Form::close() !!}
