@props(['status'])

@if ($status === 'Disponible')
    <span class="badge bg-success">Disponible</span>
@elseif ($status === 'Reservado')
    <span class="badge bg-warning text-dark">Reservado</span>
@else
    <span class="badge bg-secondary">Mantenimiento</span>
@endif