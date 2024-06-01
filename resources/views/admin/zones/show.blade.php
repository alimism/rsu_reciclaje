@extends('adminlte::page')

@section('title', 'Asignación de Personal')

@section('content')
    <div class="p-2"> </div>
    <div class="card">
        <div class="card-header">Perimetro de la Zona
            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i>
                Agregar perimetro</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 card" style="min-height: 50px">
                    <div class="card-header">
                        Datos de la Zona
                    </div>
                    <div class="card-body">
                        <label for="">Zona:</label>
                        {{ $zone->name }}<br>
                        <label for="">Area:</label>
                        {{ $zone->area }}<br>
                        <label for="">Descripción:</label>
                        {{ $zone->description }}
                    </div>
                </div>
                <div class="col-8 card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>LATITUD</th>
                                    <th>LONGITUD</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card col-12" style="min-height: 100px"></div>
            </div>
        </div>


    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulario de Coordenadas de Zona</h5>
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
                    url: "{{ route('admin.zonecoords.create') }}",
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
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
