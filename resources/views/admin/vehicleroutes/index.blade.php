@extends('layouts.app')

@section('title', 'Programación de Rutas de Vehiculos')

@section('plugins.DateRangePicker', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Datatables', true)

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <a id="btnNuevo" class="btn btn-success float-right"><i class="fas fa-plus"></i> Nuevo</a>
            <h4>Listado de Programaciones</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="dateRange" class="form-control" placeholder="Seleccione rango de fechas">
                </div>
                <div class="col-md-4">
                    <select id="vehicleFilter" class="form-control">
                        <option value="">Todos los vehículos</option>
                        @foreach ($vehicleroutes->pluck('vehicle.name')->unique() as $vehicle)
                            <option value="{{ $vehicle }}">{{ $vehicle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="routeFilter" class="form-control">
                        <option value="">Todas las rutas</option>
                        @foreach ($vehicleroutes->pluck('route.name')->unique() as $route)
                            <option value="{{ $route }}">{{ $route }}</option>
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
                <tbody>
                    @foreach ($vehicleroutes as $vehicleroute)
                        <tr>
                            <td>{{ $vehicleroute->id }}</td>
                            <td>{{ $vehicleroute->date_route }}</td>
                            <td>{{ $vehicleroute->time_route }}</td>
                            <td>{{ $vehicleroute->routeStatus->name }}</td>
                            <td>{{ $vehicleroute->vehicle->name }}</td>
                            <td>{{ $vehicleroute->route->name }}</td>
                            <td>{{ $vehicleroute->description }}</td>
                            <td><a class="btn btn-primary btnEditar btn-sm" data-id="{{ $vehicleroute->id }}"><i
                                        class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <form action="{{ route('admin.vehicleroutes.destroy', $vehicleroute->id) }}" method="post"
                                    class="frmEliminar">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
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
                    cancelLabel: 'Clear'
                },
                startDate: minDate,
                endDate: maxDate,
                opens: 'left',
                autoUpdateInput: false
            });

            // Configuración de DataTables
            var table = $('#datatable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json"
                },
                paging: true,
            });

            // Función para actualizar la tabla y los filtros
            function updateTableAndFilters() {
                table.draw();
            }

            // Manejar eventos del DateRangePicker
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                updateTableAndFilters();
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                updateTableAndFilters();
            });

            // Filtro personalizado para rango de fechas
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var dateRange = $('#dateRange').val().split(' - ');
                var min = dateRange[0] ? moment(dateRange[0], 'YYYY-MM-DD') : null;
                var max = dateRange[1] ? moment(dateRange[1], 'YYYY-MM-DD') : null;
                var date = moment(data[1], 'YYYY-MM-DD'); // Asume que la fecha está en la segunda columna

                if ((min === null && max === null) ||
                    (min === null && date <= max) ||
                    (min <= date && max === null) ||
                    (min <= date && date <= max)) {
                    return true;
                }
                return false;
            });

            // Manejar los filtros de select
            $('#vehicleFilter, #routeFilter').on('change', function() {
                updateTableAndFilters();
            });

            // Agregar la funcionalidad de filtro de select
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var vehicle = $('#vehicleFilter').val();
                var route = $('#routeFilter').val();

                if ((vehicle === '' || data[4] === vehicle) &&
                    (route === '' || data[5] === route)) {
                    return true;
                }
                return false;
            });

            // Botones y funcionalidades adicionales
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

            $('.btnEditar').click(function() {
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
