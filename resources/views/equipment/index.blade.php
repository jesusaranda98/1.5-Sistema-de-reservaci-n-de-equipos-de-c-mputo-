@extends('layouts.app')

@section('title', 'Equipos de Cómputo')

@section('content')
<div class="card main-card bg-white p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                {{ $isAdmin ? 'Administración de Equipos de Cómputo' : 'Equipos Disponibles' }}
            </h3>
            <p class="text-muted small mb-0">
                Usuario: <strong>{{ auth()->user()->name }}</strong> ·
                Sesión ID: <code>{{ \Illuminate\Support\Str::limit($sessionId, 14) }}</code>
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if ($isAdmin)
                <form method="GET" action="{{ route('equipment.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="Disponible" @selected($statusFilter === 'Disponible')>Disponible</option>
                        <option value="Reservado" @selected($statusFilter === 'Reservado')>Reservado</option>
                        <option value="Mantenimiento" @selected($statusFilter === 'Mantenimiento')>Mantenimiento</option>
                    </select>
                </form>

                <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm fw-semibold">
                    + Nuevo equipo
                </a>
            @endif
        </div>
    </div>

    <x-alert-messages />

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Equipo</th>
                    <th>Especificaciones</th>
                    <th>Ubicación</th>
                    <th class="text-center">Estado</th>
                    <th>Reservación activa</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($equipment as $item)
                    @php
                        $activeRes = $item->reservations->first();
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ $item->code }}</td>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td class="text-muted small">{{ $item->specifications }}</td>
                        <td class="small">{{ $item->lab_location }}</td>
                        <td class="text-center">
                            <x-equipment-badge :status="$item->status" />
                        </td>
                        <td class="small">
                            @if ($activeRes)
                                @if ($isAdmin || (int) $activeRes->user_id === (int) auth()->id())
                                    <span class="text-dark fw-semibold">{{ $activeRes->user?->name }}</span>
                                @else
                                    <span class="text-muted">Ocupado por otro usuario</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center justify-content-end gap-1 flex-wrap">
                                @if ($item->isAvailable())
                                    <form method="POST" action="{{ route('reservations.store') }}" class="d-inline-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="computer_equipment_id" value="{{ $item->id }}">
                                        <input type="text" name="notes" class="form-control form-control-sm"
                                               style="width: 140px;" placeholder="Nota (opcional)">
                                        <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                            Reservar
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted border">No disponible</span>
                                @endif

                                @if ($isAdmin)
                                    <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('equipment.destroy', $item) }}"
                                          onsubmit="return confirm('¿Eliminar el equipo {{ $item->code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No hay equipos disponibles en este momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection