@extends('layouts.app')

@section('title', 'Reservaciones')

@section('content')
<div class="card main-card bg-white p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                {{ $isAdmin ? 'Todas las Reservaciones' : 'Mis Reservaciones' }}
            </h3>
            <p class="text-muted small mb-0">
                Usuario autenticado: <strong>{{ auth()->user()->name }}</strong>
            </p>
        </div>

        <a href="{{ route('equipment.index') }}" class="btn btn-outline-primary btn-sm fw-semibold">
            Ver equipos
        </a>
    </div>

    <x-alert-messages />

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    @if ($isAdmin)
                        <th>Usuario</th>
                    @endif
                    <th>Notas</th>
                    <th>Fecha</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $res)
                    <tr>
                        <td class="text-muted">#{{ $res->id }}</td>
                        <td class="fw-semibold">
                            {{ $res->equipment?->code }} · {{ $res->equipment?->name }}
                        </td>
                        @if ($isAdmin)
                            <td>{{ $res->user?->name }} <span class="text-muted small">({{ $res->user?->email }})</span></td>
                        @endif
                        <td class="text-muted small">{{ $res->notes ?: '—' }}</td>
                        <td class="small text-muted">{{ $res->reserved_at?->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @if ($res->status === 'Activa')
                                <span class="badge bg-success">Activa</span>
                            @elseif ($res->status === 'Cancelada')
                                <span class="badge bg-danger">Cancelada</span>
                            @else
                                <span class="badge bg-secondary">{{ $res->status }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-dark">
                                    Detalle
                                </a>
                                @if ($res->isActive())
                                    <form method="POST" action="{{ route('reservations.cancel', $res) }}"
                                          onsubmit="return confirm('¿Cancelar la reservación #{{ $res->id }}?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-center py-4 text-muted">
                            No hay reservaciones registradas para mostrar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection