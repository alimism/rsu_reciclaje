<div class="form-group">
    @if (isset($vehicleroute))
        {!! Form::label('date_route', 'Fecha de la Ruta') !!}
        <div id="reportrange"
            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <input type="hidden" name="date_route" id="date_start"
            value="{{ old('date_route', isset($vehicleroute) ? $vehicleroute->date_route : '') }}">
    @else
        {!! Form::label('date_range', 'Rango de Fechas') !!}
        <div id="reportrange"
            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
        <input type="hidden" name="date_start" id="date_start" value="{{ old('date_start') }}">
        <input type="hidden" name="date_end" id="date_end" value="{{ old('date_end') }}">
    @endif
</div>



<div class="form-group">
    <input type="checkbox" id="exclude_weekends" name="exclude_weekends">
    {!! Form::label('exclude_weekends', 'Excluir fines de semana') !!}
</div>

<div class="form-group">
    {!! Form::label('time_route', 'Hora de la Ruta') !!}
    <div class="input-group date" id="timepicker" data-target-input="nearest">
        <input type="text" name="time_route" class="form-control datetimepicker-input" data-target="#timepicker"
            value="{{ old('time_route', isset($vehicleroute) ? $vehicleroute->time_route : '') }}" required />
        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-clock"></i></div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('routestatus_id', 'Estado de la Ruta') !!}
    {!! Form::select('routestatus_id', $routeStatuses, null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('route_id', 'Ruta') !!}
    {!! Form::select('route_id', $routes, null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('vehicle_id', 'Vehículo') !!}
    {!! Form::select('vehicle_id', $vehicles, null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripción']) !!}
</div>

<script type="text/javascript">
    $(function() {
        moment.locale('es');

        @if (isset($vehicleroute))
            var singleDate = true;
            var start = moment("{{ $vehicleroute->date_route }}");
            var end = moment("{{ $vehicleroute->date_route }}");
            $('#reportrange span').html(start.format('MMMM D, YYYY'));
        @else
            var singleDate = false;
            var start = moment();
            var end = moment().add(29, 'days');
        @endif

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + (singleDate ? '' : ' - ' + end.format(
                'MMMM D, YYYY')));
            $('#date_start').val(start.format('YYYY-MM-DD'));
            $('#date_end').val(end.format('YYYY-MM-DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            singleDatePicker: singleDate,
            ranges: singleDate ? {} : {
                'Hoy': [moment(), moment()],
                'Próximos 7 días': [moment(), moment().add(6, 'days')],
                'Próximos 30 días': [moment(), moment().add(29, 'days')],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Próximo Mes': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month')
                    .endOf('month')
                ]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                    'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                firstDay: 1
            }
        }, cb);

        cb(start, end);

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            $('#date_start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#date_end').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('#exclude_weekends').change(function() {
            if ($(this).is(':checked')) {
                $('#reportrange').daterangepicker({
                    isInvalidDate: function(date) {
                        return (date.day() === 0 || date.day() === 6);
                    }
                }, cb);
            } else {
                $('#reportrange').daterangepicker({
                    isInvalidDate: function(date) {
                        return false;
                    }
                }, cb);
            }
        });

        $('#timepicker').datetimepicker({
            format: 'HH:mm',
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

        @if (isset($vehicleroute))
            $('#timepicker').datetimepicker('date', moment('{{ $vehicleroute->time_route }}', 'HH:mm'));
        @endif
    });
</script>
