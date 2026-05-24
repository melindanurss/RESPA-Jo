<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    
    protected $fillable = ['room_id', 'room_name', 'device_id', 'current_temperature', 'current_humidity', 'status', 'last_update'];

    protected $casts = ['current_temperature' => 'decimal:2', 'current_humidity' => 'decimal:2', 'last_update' => 'datetime'];
}