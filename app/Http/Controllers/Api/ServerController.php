<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        
        return response()->json([
            'success' => true,
            'data' => $servers
        ]);
    }

    public function overview()
    {
        $totalServers = Server::count();
        $activeServers = Server::where('status', 'normal')->count();
        $averageTemperature = Server::avg('temperature');
        $averageCpu = Server::avg('cpu_usage');

        return response()->json([
            'success' => true,
            'data' => [
                'total_servers' => $totalServers,
                'active_servers' => $activeServers,
                'average_temperature' => round($averageTemperature, 1),
                'power_usage' => round($averageCpu) // dummy, bisa disesuaikan
            ]
        ]);
    }
}