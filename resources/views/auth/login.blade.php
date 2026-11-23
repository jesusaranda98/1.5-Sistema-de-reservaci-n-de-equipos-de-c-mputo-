@extends('layouts.app')

@section('title', 'Iniciar Sesión · Reservación de Equipos')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card main-card bg-white p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-2">
                    Práctica 1.5 · Sesiones Independientes
                </span>
                <h3 class="fw-bold text-dark mb-1">Iniciar Sesión</h3>
                <p class="text-muted small mb-0">
                    Reservación de equipos de cómputo en laboratorio
                </p>
            </div>

            <x-alert-messages />

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-lg" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="form-control form-control-lg" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">Recordar sesión</label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                    Entrar al sistema
                </button>
            </form>

            <hr class="my-4">
            <div class="bg-light rounded-3 p-3 small text-muted">
                <div class="fw-semibold text-dark mb-1">Cuentas de prueba:</div>
                <div><strong>Administrador:</strong> admin@laboratorio.local / <code>Admin1234!</code></div>
                <div><strong>Estudiante 1:</strong> estudiante1@laboratorio.local / <code>Estudiante1234!</code></div>
                <div><strong>Estudiante 2:</strong> estudiante2@laboratorio.local / <code>Estudiante1234!</code></div>
            </div>
        </div>
    </div>
</div>
@endsection