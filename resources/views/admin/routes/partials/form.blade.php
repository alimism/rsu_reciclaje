<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre de la ruta',
        'required',
    ]) !!}
</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('latitude_start', 'Latitud de Inicio') !!}
        {!! Form::number('latitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud de inicio',
            'required',
            'step' => 'any',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude_start', 'Longitud de Inicio') !!}
        {!! Form::number('longitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud de inicio',
            'required',
            'step' => 'any',
        ]) !!}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('latitude_end', 'Latitud de Fin') !!}
        {!! Form::number('latitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud de fin',
            'required',
            'step' => 'any',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude_end', 'Longitud de Fin') !!}
        {!! Form::number('longitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud de fin',
            'required',
            'step' => 'any',
        ]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('status', 'Estado') !!}
    <div class="form-check">
        {!! Form::checkbox('status', 1, isset($route) ? $route->status : false, [
            'class' => 'form-check-input',
            'id' => 'statusCheckbox',
        ]) !!}
        <label class="form-check-label" for="statusCheckbox">Activo</label>
    </div>
</div>
