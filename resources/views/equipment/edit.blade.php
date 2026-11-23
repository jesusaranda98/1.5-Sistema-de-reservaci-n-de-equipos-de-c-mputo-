@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card main-card bg-white p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Editar Equipo {{ $equipment->code }}</h4>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
            </div>

            <x-alert-messages />

            <form method="POST" action="{{ route('equipment.update', $equipment) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="code" class="form-label fw-semibold">Código del equipo</label>
                    <input type="text" id="code" name="code" value="{{ old('code', $equipment->code) }}"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nombre</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $equipment->name) }}"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="specifications" class="form-label fw-semibold">Especificaciones</label>
                    <input type="text" id="specifications" name="specifications"
                           value="{{ old('specifications', $equipment->specifications) }}" class="form-control" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="lab_location" class="form-label fw-semibold">Ubicación / Laboratorio</label>
                        <input type="text" id="lab_location" name="lab_location"
                               value="{{ old('lab_location', $equipment->lab_location) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Estado</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="Disponible" @selected(old('status', $equipment->status) === 'Disponible')>Disponible</option>
                            <option value="Reservado" @selected(old('status', $equipment->status) === 'Reservado')>Reservado</option>
                            <option value="Mantenimiento" @selected(old('status', $equipment->status) === 'Mantenimiento')>Mantenimiento</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">Actualizar equipo</button>
            </form>
        </div>
    </div>
</div>
@endsection