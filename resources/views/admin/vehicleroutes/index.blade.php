@extends('layouts.app')

@section('title', 'Programación de Rutas de Vehiculos')

@section('plugins.DateRangePicker', true)
@section('plugins.TempusDominusBs4', true)

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i> Nuevo</a>
            <h4>Listado de Programaciones</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <input type="text" id="dateRange" class="form-control" placeholder="Seleccione rango de fechas">
                </div>
                <div class="col-md-6">
                    <div class="input-group date" id="timePicker" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#timePicker"
                            placeholder="Seleccione hora" />
                        <div class="input-group-append" data-target="#timePicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <select id="vehicleFilter" class="form-control">
                        <option selected value>Selecciona un vehiculo</option>
                        @foreach (App\Models\Vehicle::pluck('name', 'id') as $id => $vehicle)
                            <option value="{{ $id }}">{{ $vehicle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select id="routeFilter" class="form-control">
                        <option selected value>Selecciona una ruta</option>
                        @foreach (App\Models\Route::pluck('name', 'id') as $id => $route)
                            <option value="{{ $id }}">{{ $route }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Vehículo</th>
                        <th>Ruta</th>
                        <th>Descripción</th>
                        <th width="10"></th>
                        <th width="10"></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Programación</h5>
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
            var minDate = "{{ $minDate }}";
            var maxDate = "{{ $maxDate }}";

            // Inicializar el DateRangePicker
            $('#dateRange').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Limpiar'
                },
                startDate: minDate,
                endDate: maxDate,
                opens: 'left',
                autoUpdateInput: true
            });

            // Inicializar el TimePicker
            $('#timePicker').datetimepicker({
                format: 'HH:mm', // Formato de horas y minutos
                locale: 'es',
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-bullseye',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            // Inicializar los valores desde la sesión
            if ("{{ session('date_range') }}") {
                var dateRange = "{{ session('date_range') }}".split(' - ');
                $('#dateRange').data('daterangepicker').setStartDate(dateRange[0]);
                $('#dateRange').data('daterangepicker').setEndDate(dateRange[1]);
                $('#dateRange').val("{{ session('date_range') }}");
            }

            if ("{{ session('time_picker') }}") {
                $('#timePicker').find("input").val("{{ session('time_picker') }}");
                $('#timePicker').datetimepicker('date', moment("{{ session('time_picker') }}", 'HH:mm'));
            }

            if ("{{ session('vehicle_filter') }}") {
                $('#vehicleFilter').val("{{ session('vehicle_filter') }}");
            }

            if ("{{ session('route_filter') }}") {
                $('#routeFilter').val("{{ session('route_filter') }}");
            }

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                applyFilters();
            });

            // Limpiar el valor del input al cancelar la selección
            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                // applyFilters();
                

            });

            $('#timePicker').on('change.datetimepicker', function(ev) {
                const valorInput = $('#timePicker').find("input").val();
                const isEmpty = valorInput === '' ? true : false;
                if (!isEmpty) {
                    applyFilters();
                } else {
                    $('#tableBody').empty();
                }
                // applyFilters();
            });

            $('#vehicleFilter, #routeFilter').on('change', function() {
                applyFilters();
            });

            function applyFilters() {
                var dateRange = $('#dateRange').val().split(' - ');
                var time = $('#timePicker').find("input").val();
                if (time !== '') {
                    time = time + ':00'; // Normalizar los segundos a 00
                }
                var vehicle = $('#vehicleFilter').val();
                var route = $('#routeFilter').val();

                $.ajax({
                    url: "{{ route('admin.vehicleroutes.filter') }}",
                    method: "GET",
                    data: {
                        date_start: dateRange[0],
                        date_end: dateRange[1],
                        time_route: time,
                        vehicle: vehicle,
                        route: route
                    },
                    success: function(data) {
                        $('#tableBody').empty();
                        data.forEach(function(route) {
                            $('#tableBody').append(`
                            <tr>
                                <td>${route.id}</td>
                                <td>${route.date_route}</td>
                                <td>${route.time_route}</td>
                                <td>${route.route_status ? route.route_status.name : ''}</td>
                                <td>${route.vehicle ? route.vehicle.name : ''}</td>
                                <td>${route.route ? route.route.name : ''}</td>
                                <td>${route.description}</td>
                                <td><a class="btn btn-primary btnEditar btn-sm" data-id="${route.id}"><i class="fas fa-edit"></i></a></td>
                                <td>
                                    <form action="/admin/vehicleroutes/${route.id}" method="post" class="frmEliminar">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        `);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching filtered data:', xhr.responseText);
                    }
                });

                // Guardar filtros en la sesión
                $.ajax({
                    url: "{{ route('admin.vehicleroutes.storeFilters') }}",
                    method: "POST",
                    data: {
                        date_range: $('#dateRange').val(),
                        time_picker: $('#timePicker').find("input").val(),
                        vehicle_filter: $('#vehicleFilter').val(),
                        route_filter: $('#routeFilter').val(),
                        _token: '{{ csrf_token() }}'
                    }
                });
            }

            $('#btnNuevo').click(function() {
                $.ajax({
                    url: "{{ route('admin.vehicleroutes.create') }}",
                    type: "GET",
                    success: function(response) {
                        $('#exampleModal .modal-body').html(response);
                        $('#exampleModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.btnEditar', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.vehicleroutes.edit', ['vehicleroute' => '__id__']) }}"
                        .replace('__id__', id),
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

            $(document).on('submit', '.frmEliminar', function(e) {
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

            // No aplicar filtros al cargar la página
        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Error de Proceso",
                html: `{!! session('error') !!}`,
                icon: "error"
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Proceso Exitoso",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('validationErrors'))
        <script>
            let errorMessages = @json(session('validationErrors'));
            let formattedErrors = errorMessages.join('<br>');
            Swal.fire({
                title: "Error de Validación",
                html: formattedErrors,
                icon: "error"
            });
        </script>
    @endif
@stop
