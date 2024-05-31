<div class="form-row">

    <div class="form-group col-4">
        {!! Form::label('usertype_id', 'Tipo de Persona') !!}
        {!! Form::select('usertype_id', $usertype, isset($user) ? $user->usertype_id : null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-4">
        {!! Form::label('DNI', 'DNI') !!}
        {!! Form::text('DNI', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese DNI de la persona',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-4">
        {!! Form::label('license', 'Licencia de conducir(opcional)') !!}
        {!! Form::text('license', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese licencia de conducir',
            // 'required',
        ]) !!}
    </div>

</div>

<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('name', 'Nombres') !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese nombres de la persona',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('lastname', 'Apellidos') !!}
        {!! Form::text('lastname', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese apellidos de la persona',
            'required',
        ]) !!}
    </div>

</div>

<div class="form-row">

    <div class="form-group col-6">
        {!! Form::label('email', 'Email') !!}
        {!! Form::text('email', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese email de acceso',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('password', 'Contraseña') !!}
        {!! Form::password('password', [
            'class' => 'form-control',
            'placeholder' => 'Ingrese contraseña',
            // 'required',
        ]) !!}
    </div>

</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('zone_id', 'Zona') !!}
        {!! Form::select('zone_id', $zone, isset($user) ? $user->zone_id : null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('address', 'Dirección de domicilio') !!}
        {!! Form::text('address', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese dirección de domicilio',
            // 'required',
        ]) !!}
    </div>
</div>


<div class="form-row">
    <div class="form-group col-6">
        <label form="formFile" class="form-label">Suba una imagen de Perfil</label>
        <input type="file" name="profile" id="profile" class="form-control" accept="image/*">
    </div>
    <div class="form-group col-6">
        {!! Form::label('birthdate', 'Fecha de Nacimiento') !!}
        {!! Form::date('birthdate', null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese fecha',
            // 'required',
        ]) !!}
    </div>
</div>


{{-- <div class="form-group">
    {!! Form::label('brand_id', 'Marca') !!}
    {!! Form::select('brand_id', $brands, isset($model) ? $model->brand_id : null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div> --}}

