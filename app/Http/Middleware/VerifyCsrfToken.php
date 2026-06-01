<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/contohinput',
        '/monitoring/bme280',
        '/monitoringsuhu',
        '/rooms',
        '/api/*'
    ];
}