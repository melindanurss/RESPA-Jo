<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $table = 'server_monitorings';

    protected $fillable = ['device_key', 'ruang', 'suhu', 'kelembapan', 'status', 'last_status'];

    protected $casts = ['suhu' => 'decimal:2', 'kelembapan' => 'decimal:2'];
}