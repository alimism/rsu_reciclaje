{{-- @extends('layouts.app')

@section('title', 'Editar Marca')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Editar Marca</h4>
        </div>
        <div class="card-body">
            {!! Form::model($route, ['route' => ['admin.routes.update', $route->id], 'method' => 'put']) !!}

            @include('admin.routes.partials.form')

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
            <button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>

            {!! Form::close() !!}
        </div>
        <div class="card-footer">

        </div>
    </div>

@stop --}}

{!! Form::model($route, ['route' => ['admin.routes.update', $route->id], 'method' => 'put', 'files' => true]) !!}

@include('admin.routes.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
<button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>

{!! Form::close() !!}
