@extends('layouts.app')

@section('title', 'Detalle de Reservación #' . $reservation->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card main-card bg-white p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Reservación #{{ $reservation->id }}</h4>
                <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
            </div>

            <dl class="row mb-4 small">
                <dt class="col-sm-4 text-muted">Usuario</dt>
                <dd class="col-sm-8 fw-semibold">{{ $reservation->user?->name }} ({{ $reservation->user?->email }})</dd>

                <dt class="col-sm-4 text-muted">Equipo</dt>
                <dd class="col-sm-8">{{ $reservation->equipment?->code }} — {{ $reservation->equipment?->name }}</dd>

                <dt class="col-sm-4 text-muted">Ubicación</dt>
                <dd class="col-sm-8">{{ $reservation->equipment?->lab_location }}</dd>

                <dt class="col-sm-4 text-muted">Estado</dt>
                <dd class="col-sm-8">{{ $reservation->status }}</dd>

                <dt class="col-sm-4 text-muted">Fecha de reserva</dt>
                <dd class="col-sm-8">{{ $reservation->reserved_at?->format('d/m/Y H:i:s') }}</dd>

                <dt class="col-sm-4 text-muted">Notas</dt>
                <dd class="col-sm-8">{{ $reservation->notes ?: 'Sin notas' }}</dd>
            </dl>

            @if ($reservation->isActive())
                <form method="POST" action="{{ route('reservations.cancel', $reservation) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger w-100 fw-semibold">
                        Cancelar reservación
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection