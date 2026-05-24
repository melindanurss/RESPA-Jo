<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring_Suhu extends Model
{
    use HasFactory;

    protected $table = 'monitoringsuhu';

    protected $fillable = ['suhu', 'kelembaban', 'status_suhu', 'status_kelembaban', 'keterangan'];
}