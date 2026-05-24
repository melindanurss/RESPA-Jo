<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['type', 'title', 'message', 'data', 'device_key', 'room_id', 'infusion_id', 'priority', 'is_read', 'read_at', 'metadata'];

    protected $casts = ['metadata' => 'array', 'data' => 'array', 'is_read' => 'boolean'];

    public function getSeverityAttribute()
    {
        return $this->priority ?? 'info';
    }

    public function getDataAttribute()
    {
        return $this->metadata ?? [];
    }
}