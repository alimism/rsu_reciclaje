<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('name', 'Nombre') !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el nombre de la unidad',
            'required',
        ]) !!}

    </div>

    <div class="form-group col-6">
        {!! Form::label('code', 'Codigo') !!}
        {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo de la unidad']) !!}

    </div>

</div>

<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('plate', 'Placa') !!}
        {!! Form::text('plate', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la placa de la unidad',
        ]) !!}

    </div>

    <div class="form-group col-6">
        {!! Form::label('year', 'Año') !!}
        {!! Form::text('year', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el año de la unidad']) !!}

    </div>

</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('brand_id', 'Marca') !!}
        {!! Form::select('brand_id', $brands, isset($vehicle) ? $vehicle->brand_id : null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('model_id', 'Modelo') !!}
        {!! Form::select('model_id', $models, isset($vehicle) ? $vehicle->model_id : null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
</div>


<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('type_id', 'Tipo') !!}
        {!! Form::select('type_id', $types, isset($vehicle) ? $vehicle->type_id : null, [
            'class' => 'form-control',
            'required',
            'id' => 'brand_id',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('color_id', 'Color') !!}
        {!! Form::select('color_id', $colors, isset($vehicle) ? $vehicle->color_id : null, [
            'class' => 'form-control',
            'required',
            'id' => 'model_id',
        ]) !!}
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12">
        {!! Form::label('description', 'Descripcion') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripcion']) !!}

    </div>
</div>

<div class="form-row">
    <div class="form-group col-12">
        {!! Form::label('status', 'Activo') !!}
        {!! Form::checkbox('status', 1, null) !!}

    </div>
</div>

<div class="form-group">
    <label form="formFile" class="form-label">Selecciona una imagen</label>
    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
</div>


<script>
    $('#brand_id').change(function() {
            var id = $(this).val();
            // alert(id);
            $.ajax({
                url: "{{ route('admin.modelsbybrand', '_id') }}".replace("_id", id),
                type: "GET",
                datatype: "JSON",
                contenttype: "application/json",
                success: function(response) {
                    $.each(response, function(key, value) {
                        $('#model_id').empty();
                        $('#model_id').append('<option value=' + value.id + '>' + value.name +
                            '</option>')

                    })
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

    )
</script>
