<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComputerEquipment extends Model
{
    use HasFactory;

    protected $table = 'computer_equipment';

    protected $fillable = [
        'code',
        'name',
        'specifications',
        'lab_location',
        'status',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'computer_equipment_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'Disponible';
    }
}