<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('name', 'Nombre') !!}
        {!! Form::text('name', isset($vehicle) ? $vehicle->name : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el nombre de la unidad',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('code', 'Codigo') !!}
        {!! Form::text('code', isset($vehicle) ? $vehicle->code : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el codigo de la unidad',
        ]) !!}
    </div>
</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('plate', 'Placa') !!}
        {!! Form::text('plate', isset($vehicle) ? $vehicle->plate : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la placa de la unidad',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('year', 'Año') !!}
        {!! Form::text('year', isset($vehicle) ? $vehicle->year : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el año de la unidad',
        ]) !!}
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
        <select name="color_id" id="color_id" class="form-control select2" required>
            @foreach($colors as $id => $color)
                <option value="{{ $id }}" data-color="{{ $color }}" {{ isset($vehicle) && $vehicle->color_id == $id ? 'selected' : '' }}>
                    {{ $color }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12">
        {!! Form::label('description', 'Descripcion') !!}
        {!! Form::textarea('description', isset($vehicle) ? $vehicle->description : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese una descripcion',
        ]) !!}
    </div>
</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('capacity', 'Capacidad') !!}
        {!! Form::number('capacity', isset($vehicle) ? $vehicle->capacity : null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la capacidad de la unidad',
        ]) !!}
    </div>

    <div class="form-group col-6">
        {!! Form::label('status', 'Activo') !!}
        {!! Form::checkbox('status', 1, isset($vehicle) ? $vehicle->status : null) !!}
    </div>
</div>

<div class="form-group">
    <label for="formFile" class="form-label">Selecciona una imagen</label>
    <input type="file" name="image" id="image" class="form-control" accept="image/*">
</div>

<script>
    $(document).ready(function() {
        $('#brand_id').change(function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ route('admin.modelsbybrand', '_id') }}".replace("_id", id),
                type: "GET",
                datatype: "JSON",
                contenttype: "application/json",
                success: function(response) {
                    $('#model_id').empty();
                    $.each(response, function(key, value) {
                        $('#model_id').append('<option value=' + value.id + '>' + value.name + '</option>');
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        function formatColorOption(color) {
            if (!color.id) {
                return color.text;
            }
            var colorValue = $(color.element).text().trim(); // Obtener el valor del texto que contiene el color hex
            var $colorOption = $('<span><i class="fa fa-square" style="color:' + colorValue + '; margin-right: 5px;"></i>' + colorValue + '</span>');
            return $colorOption;
        }

        $('#color_id').select2({
            theme: 'bootstrap-5', // Asegura que Select2 use el tema de Bootstrap
            templateResult: formatColorOption,
            templateSelection: formatColorOption,
            width: 'resolve' // Ajusta el ancho para que coincida con otros selects
        });
    });
</script>
