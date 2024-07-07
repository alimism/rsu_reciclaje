<!-- resources/views/admin/maintenances/activities.blade.php -->
<ul id="activitiesList" class="list-group">
    @foreach($activities as $activity)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $activity->date }} - {{ $activity->description }}
            <form action="{{ route('admin.maintenances.destroyActivity', [$maintenance->id, $activity->id]) }}" method="post" class="frmEliminar">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </li>
    @endforeach
</ul>
<hr>
<form id="activityForm" method="post" action="{{ route('admin.maintenances.storeActivity', $maintenance->id) }}">
    @csrf
    <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule_id }}">
    <div class="form-group">
        <label for="date">Fecha</label>
        <input type="date" name="date" id="date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Descripción</label>
        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Actividad</button>
</form>
