@extends('layouts.app')

@section('title', 'Zonas')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">


            {{-- Primera version con ruta hacia otra pagina para la creacion de una marca

            <a href="{{ route('admin.zones.create') }}" class="btn btn-success float-right"><i class="fas fa-plus"></i>
                Nuevo</a> --}}

            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i>
                Nuevo</a>

            <h4>Listado de Zonas</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>

                        <th>ID</th>
                        <th>NOMBRE</th>

                        <th>AREA</th>
                        <th>DESCRIPCION</th>
                        <th width="20"></th>
                        <th width="20"></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($zones as $zone)
                        <tr>

                            <td>{{ $zone->id }}</td>
                            <td>{{ $zone->name }}</td>
                            <td>{{ $zone->area }}</td>
                            <td>{{ $zone->description }}</td>

                            <td>
                                <a class="btn btn-secondary" href="{{ route('admin.zones.show', $zone->id) }}" data-id="{{ $zone->id }}"><i class="fas fa-map"></i></a>
                            </td>

                            <td>
                                {{-- <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-primary"><i
                                        class="fas fa-edit"></i></a> --}}
                                <a class="btn btn-primary btnEditar" data-id="{{ $zone->id }}"><i
                                        class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="post"
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Zonas</h5>
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
                    url: "{{ route('admin.zones.create') }}",
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    }
                });
            });

            $('.btnEditar').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.zones.edit', ['zone' => '__id__']) }}"
                        .replace('__id__',
                            id),
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error fetching the edit form:', xhr.responseText);
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
