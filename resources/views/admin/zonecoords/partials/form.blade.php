<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('latitude', 'Latitud') !!}
        {!! Form::text('latitude', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la latitud',
            'readonly',
            'required',
        ]) !!}

    </div>

    <div class="form-group col-6">
        {!! Form::label('longitude', 'Longitud') !!}
        {!! Form::text('area', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese la longitud',
            'readonly',
            'required',
        ]) !!}

    </div>
</div>
<div id="map" style="height: 400px; width:100%; border: 1px solid black;">

</div><br>

<script>
    
</script>
