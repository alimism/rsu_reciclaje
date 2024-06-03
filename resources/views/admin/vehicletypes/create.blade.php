{{-- @extends('layouts.app')

@section('title', 'Nueva Marca')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Nueva Marca</h4>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'admin.vehicletypes.store']) !!}

            @include('admin.vehicletypes.partials.form')

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar</button>
            <button type="button" class="btn btn-danger"><i class="fas fa-window-close"></i> Cancelar</button>

            {!! Form::close() !!}
        </div>
        <div class="card-footer">

        </div>
    </div>

@stop --}}


{!! Form::open(['route' => 'admin.vehicletypes.store', 'files' => true]) !!}


@include('admin.vehicletypes.partials.form')

<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Registrar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>

{!! Form::close() !!}



