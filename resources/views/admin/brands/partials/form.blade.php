<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre de la marca',
        'required',
    ]) !!}

</div>

<div class="form-group">
    {!! Form::label('description', 'Descripcion') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripcion']) !!}

</div>

{{-- <div class="form-group">
    {!! Form::label('logo', 'Logo') !!}
    {!! Form::text('logo', null, ['class' => 'form-control', 'placeholder' => 'Url del logo']) !!}
    
</div> --}}
<div class="form-group">
    <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
</div>
