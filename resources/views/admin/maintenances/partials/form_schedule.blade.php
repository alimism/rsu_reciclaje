<div class="form-group">
    {!! Form::label('day_of_week', 'Día de la Semana') !!}
    {!! Form::select('day_of_week', [
        'Lunes' => 'Lunes',
        'Martes' => 'Martes',
        'Miércoles' => 'Miércoles',
        'Jueves' => 'Jueves',
        'Viernes' => 'Viernes',
        'Sábado' => 'Sábado'
    ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione el día de la semana', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_time', 'Hora de Inicio') !!}
    {!! Form::time('start_time', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('end_time', 'Hora de Fin') !!}
    {!! Form::time('end_time', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('vehicle_id', 'Vehículo') !!}
    {!! Form::select('vehicle_id', $vehicles->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Seleccione un vehículo', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('type', 'Tipo de Mantenimiento') !!}
    {!! Form::select('type', [
        'Limpieza' => 'Limpieza',
        'Reparación' => 'Reparación'
    ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione el tipo de mantenimiento', 'required']) !!}
</div>
