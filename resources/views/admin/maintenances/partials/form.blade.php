<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del mantenimiento', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_date', 'Fecha de Inicio') !!}
    {!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('end_date', 'Fecha de Fin') !!}
    {!! Form::date('end_date', null, ['class' => 'form-control', 'required']) !!}
</div>
