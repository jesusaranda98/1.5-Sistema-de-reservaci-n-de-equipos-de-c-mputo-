<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['administrador', 'estudiante'], true);
    }

    public function rules(): array
    {
        return [
            'computer_equipment_id' => ['required', 'integer', Rule::exists('computer_equipment', 'id')],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'computer_equipment_id.required' => 'Debes seleccionar un equipo para reservar.',
            'computer_equipment_id.exists' => 'El equipo seleccionado no existe.',
            'notes.max' => 'Las notas no pueden exceder 500 caracteres.',
        ];
    }
}