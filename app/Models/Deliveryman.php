<?php

namespace App\Models;

use Database\Factories\DeliverymanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliveryman extends Model
{
    /** @use HasFactory<DeliverymanFactory> */
    use HasFactory;

    protected $fillable = ['name', 'employee_id', 'phone', 'vehicle', 'joined_at', 'assigned_areas'];

    protected function casts(): array
    {
        return ['assigned_areas' => 'array'];
    }
}
