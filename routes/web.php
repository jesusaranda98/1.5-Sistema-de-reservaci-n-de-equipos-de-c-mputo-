<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Equipos y reservaciones: Administrador y Estudiante
Route::middleware(['auth', 'role:administrador,estudiante'])->group(function () {
    Route::get('/equipos', [EquipmentController::class, 'index'])->name('equipment.index');

    Route::get('/reservaciones', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservaciones/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservaciones', [ReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/reservaciones/{reservation}/cancelar', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

// Administración de equipos: solo Administrador
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/equipos/crear', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('/equipos', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('/equipos/{equipment}/editar', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('/equipos/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/equipos/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
});