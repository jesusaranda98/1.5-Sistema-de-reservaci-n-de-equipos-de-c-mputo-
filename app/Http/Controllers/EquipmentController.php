<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\ComputerEquipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $statusFilter = (string) $request->input('status', '');

        $query = ComputerEquipment::query()
            ->with(['reservations' => function ($q) {
                $q->where('status', 'Activa')->with('user');
            }])
            ->when(! $user->isAdmin(), function ($q) {
                $q->where('status', 'Disponible');
            })
            ->when($user->isAdmin() && in_array($statusFilter, ['Disponible', 'Reservado', 'Mantenimiento'], true), function ($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });

        $equipment = $query->orderBy('code')->get();

        return view('equipment.index', [
            'equipment' => $equipment,
            'isAdmin' => $user->isAdmin(),
            'statusFilter' => $statusFilter,
            'sessionId' => $request->session()->getId(),
        ]);
    }

    public function create(): View
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Solo los administradores pueden registrar equipos.');
        }

        return view('equipment.create');
    }

    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ComputerEquipment::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
            'specifications' => trim($validated['specifications']),
            'lab_location' => trim($validated['lab_location']),
            'status' => $validated['status'],
        ]);

        return redirect()->route('equipment.index')
            ->with('success', 'Equipo registrado correctamente.');
    }

    public function edit(ComputerEquipment $equipment): View
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Solo los administradores pueden actualizar equipos.');
        }

        return view('equipment.edit', [
            'equipment' => $equipment,
        ]);
    }

    public function update(UpdateEquipmentRequest $request, ComputerEquipment $equipment): RedirectResponse
    {
        $validated = $request->validated();

        $equipment->update([
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
            'specifications' => trim($validated['specifications']),
            'lab_location' => trim($validated['lab_location']),
            'status' => $validated['status'],
        ]);

        return redirect()->route('equipment.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(ComputerEquipment $equipment): RedirectResponse
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Solo los administradores pueden eliminar equipos.');
        }

        if ($equipment->reservations()->where('status', 'Activa')->exists()) {
            return back()->withErrors([
                'equipment' => 'No se puede eliminar un equipo con una reservación activa.',
            ]);
        }

        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }
}