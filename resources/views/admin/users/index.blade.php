@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">

            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i>
                Nuevo</a>

            <h4>Listado de Usuarios</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>DNI</th>
                        <th>NOMBRES</th>
                        <th>EMAIL</th>
                        <th>DIRECCIÓN</th>
                        <th>TIPO</th>
                        <th>ESTADO</th>
                        <th width="20">EDITAR</th>
                        <th width="20">ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>

                                <img src="{{ asset($user->profile_photo_path ?: 'storage/users_profile/default_profile.png') }}" width="50">
                            </td>
                            <td>{{ $user->DNI }}</td>
                            <td>{{ $user->name }} {{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->type ? $user->type->name : 'N/A' }}</td>
                            <td class="{{ $user->status == 1 ? 'text-success' : 'text-danger' }}">
                                {{ $user->status == 1 ? 'Activo' : 'Inactivo' }}
                            </td>

                            <td>
                                {{-- <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary"><i
                                        class="fas fa-edit"></i></a> holi x3 --}}
                                <a class="btn btn-primary btnEditar" data-id="{{ $user->id }}"><i
                                        class="fas fa-edit"></i></a>

                            </td>
                            <td>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="post"
                                    class="frmEliminar">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>

    {{-- <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
  </button> --}}

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                }
            });

            $('#btnNuevo').click(function() {
                $.ajax({
                    url: "{{ route('admin.users.create') }}",
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    }
                });
            });

            $('.btnEditar').on('click', function() {
                var id = $(this).data('id');
                var url = "{{ route('admin.users.edit', ['user' => ':id']) }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });


            $('.frmEliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "¿Está seguro de eliminar?",
                    text: "Esta acción es irreversible",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Proceso Exitoso",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Error de Proceso",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif
@stop
