<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = ['device_key', 'device_type', 'is_active', 'last_active', 'last_activity', 'name', 'location', 'description'];

    protected $casts = ['is_active' => 'boolean', 'last_active' => 'datetime'];

    public function serverData()
    {
        return $this->hasMany(Server::class, 'device_key', 'device_key');
    }

    public function room()
    {
        return $this->hasOne(Rooms::class, 'device_id', 'device_key');
    }
}