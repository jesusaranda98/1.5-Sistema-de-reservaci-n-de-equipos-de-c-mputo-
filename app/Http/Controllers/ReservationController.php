<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\ComputerEquipment;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $reservations = Reservation::query()
            ->with(['user', 'equipment'])
            ->when(! $user->isAdmin(), function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->get();

        return view('reservations.index', [
            'reservations' => $reservations,
            'isAdmin' => $user->isAdmin(),
        ]);
    }

    public function show(Reservation $reservation): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin() && (int) $reservation->user_id !== (int) $user->id) {
            abort(403, 'Acceso denegado: no puedes consultar las reservaciones privadas de otro usuario.');
        }

        $reservation->load(['user', 'equipment']);

        return view('reservations.show', [
            'reservation' => $reservation,
            'isAdmin' => $user->isAdmin(),
        ]);
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validated();
        $equipmentId = (int) $validated['computer_equipment_id'];

        $result = DB::transaction(function () use ($user, $equipmentId, $validated) {
            /** @var ComputerEquipment|null $equipment */
            $equipment = ComputerEquipment::query()
                ->whereKey($equipmentId)
                ->lockForUpdate()
                ->first();

            if (! $equipment) {
                return ['ok' => false, 'message' => 'El equipo solicitado no existe.'];
            }

            if ($equipment->status !== 'Disponible') {
                return [
                    'ok' => false,
                    'message' => "El equipo {$equipment->code} ya no está disponible (estado actual: {$equipment->status}).",
                ];
            }

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'computer_equipment_id' => $equipment->id,
                'notes' => isset($validated['notes']) ? trim($validated['notes']) : null,
                'status' => 'Activa',
                'reserved_at' => now(),
            ]);

            $equipment->update([
                'status' => 'Reservado',
            ]);

            return ['ok' => true, 'reservation' => $reservation, 'equipment' => $equipment];
        });

        if (! $result['ok']) {
            return redirect()->route('equipment.index')
                ->withErrors(['reservation' => $result['message']]);
        }

        return redirect()->route('reservations.index')
            ->with('success', "Reservación #{$result['reservation']->id} confirmada para el equipo {$result['equipment']->code}.");
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin() && (int) $reservation->user_id !== (int) $user->id) {
            abort(403, 'Acceso denegado: no puedes cancelar una reservación que pertenece a otro usuario.');
        }

        DB::transaction(function () use ($reservation) {
            /** @var Reservation $lockedReservation */
            $lockedReservation = Reservation::query()
                ->whereKey($reservation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedReservation->status !== 'Activa') {
                return;
            }

            $lockedReservation->update([
                'status' => 'Cancelada',
                'cancelled_at' => now(),
            ]);

            /** @var ComputerEquipment|null $equipment */
            $equipment = ComputerEquipment::query()
                ->whereKey($lockedReservation->computer_equipment_id)
                ->lockForUpdate()
                ->first();

            if ($equipment && $equipment->status === 'Reservado') {
                $equipment->update([
                    'status' => 'Disponible',
                ]);
            }
        });

        return redirect()->route('reservations.index')
            ->with('success', 'Reservación cancelada y equipo liberado.');
    }
}