<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'computer_equipment_id',
        'notes',
        'status',
        'reserved_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(ComputerEquipment::class, 'computer_equipment_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'Activa';
    }
}