<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre del modelo',
        'required',
    ]) !!}

</div>

<div class="form-group">
    {!! Form::label('brand_id', 'Marca') !!}
    {!! Form::select('brand_id', $brands, isset($model) ? $model->brand_id : null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div>


<div class="form-group">
    {!! Form::label('code', 'Codigo') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo del modelo']) !!}

</div>

<div class="form-group">
    {!! Form::label('description', 'Descripcion') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripcion']) !!}

</div>
