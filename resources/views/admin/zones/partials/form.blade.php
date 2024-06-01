<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre de la zona',
        'required',
    ]) !!}

</div>

<div class="form-group">
    {!! Form::label('area', 'Area') !!}
    {!! Form::text('area', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el area de la zona',
        'required',
    ]) !!}

</div>

<div class="form-group">
    {!! Form::label('description', 'Descripcion') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripcion']) !!}

</div>

