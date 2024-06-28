<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    <div class="input-group colorpicker-component">
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el color del vehiculo',
            'required',
        ]) !!}
        <span class="input-group-append">
            <span class="input-group-text colorpicker-input-addon">
                <i></i>
            </span>
        </span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripcion') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripcion']) !!}
</div>

<script>
    $(document).ready(function() {
        $('.colorpicker-component').colorpicker({
            format: 'hex'
        });
    });
</script>
