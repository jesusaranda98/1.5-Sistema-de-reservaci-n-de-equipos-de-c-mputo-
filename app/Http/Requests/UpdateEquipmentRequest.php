<?php

namespace App\Http\Requests;

use App\Models\ComputerEquipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'administrador';
    }

    public function rules(): array
    {
        $routeEquipment = $this->route('equipment');
        $equipmentId = $routeEquipment instanceof ComputerEquipment ? $routeEquipment->id : $routeEquipment;

        return [
            'code' => [
                'required',
                'string',
                'max:40',
                Rule::unique('computer_equipment', 'code')->ignore($equipmentId),
            ],
            'name' => ['required', 'string', 'max:120'],
            'specifications' => ['required', 'string', 'max:255'],
            'lab_location' => ['required', 'string', 'max:120'],
            'status' => ['required', Rule::in(['Disponible', 'Reservado', 'Mantenimiento'])],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código del equipo es obligatorio.',
            'code.unique' => 'Ese código ya pertenece a otro equipo.',
            'name.required' => 'El nombre del equipo es obligatorio.',
            'specifications.required' => 'Las especificaciones técnicas son obligatorias.',
            'lab_location.required' => 'La ubicación o laboratorio es obligatoria.',
            'status.required' => 'Debes seleccionar el estado del equipo.',
            'status.in' => 'El estado debe ser Disponible, Reservado o Mantenimiento.',
        ];
    }
}