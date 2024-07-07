@extends('layouts.app')

@section('title', 'Detalle del Mantenimiento')

@section('content')
    <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i>
        Regresar</a>

    <div class="card mt-3">
        <div class="card-header">
            <a id="btnNuevoHorario" class="btn btn-success float-right"><i class="fas fa-plus"></i> Nuevo Horario</a>
            <h4>Horarios del Mantenimiento</h4>
        </div>
        <div class="card-body">

            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Vehículo</th>
                        <th>Conductor</th>
                        <th>Tipo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th width="10">EDITAR</th>
                        <th width="10">ACT</th>
                        <th width="10">ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->day_of_week }}</td>
                            <td>{{ $schedule->vehicle->name }}</td>
                            <td>{{ optional($schedule->vehicle->occupants->where('usertype_id', 3)->where('status', 1)->first())->user->name }}</td>
                            <td>{{ $schedule->type }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>
                                <a class="btn btn-primary btnEditarHorario" data-id="{{ $schedule->id }}"><i class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <a class="btn btn-info btnVerActividades" data-id="{{ $schedule->id }}"><i class="fas fa-tasks"></i></a>
                            </td>
                            <td>
                                <form action="{{ route('admin.maintenances.destroySchedule', [$maintenance->id, $schedule->id]) }}" method="post" class="frmEliminar">
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

    <!-- Modal para Horarios -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Horario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Actividades -->
    <div class="modal fade" id="activitiesModal" tabindex="-1" role="dialog" aria-labelledby="activitiesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activitiesModalLabel">Actividades del Mantenimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json"
                }
            });

            $('#btnNuevoHorario').click(function() {
                $.ajax({
                    url: "{{ route('admin.maintenances.createSchedule', $maintenance->id) }}",
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    }
                });
            });

            $('.btnEditarHorario').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.maintenances.editSchedule', [$maintenance->id, '__id__']) }}".replace('__id__', id),
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

            $('.btnVerActividades').click(function() {
                var scheduleId = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.maintenances.activities', ['maintenance' => $maintenance->id]) }}",
                    type: "GET",
                    data: { schedule_id: scheduleId },
                    success: function(response) {
                        $('#activitiesModal .modal-body').html(response);
                        $('#activitiesModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error fetching activities:', xhr.responseText);
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
