<?php

namespace App\Http\Controllers;
use App\Models\Server;
use Illuminate\Http\Request;

class MonitoringServerController extends Controller
{
    public function getServers()
    {
        $servers = Server::all();
        return response()->json(['success' => true, 'data' => $servers]);
    }

    public function index()
    {
        return view('server');
    }
    
    public function store(Request $request)
    {
        $servers = Server::orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $servers]);
    }

    public function getOverview()
    {
        $totalServers = Server::count();
        $activeServers = Server::where('status', 'normal')->count();
        $averageTemperature = Server::avg('suhu') ?? 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_servers' => $totalServers, 'active_servers' => $activeServers,
                'average_temperature' => round($averageTemperature, 1), 'power_usage' => 78
            ]
        ]);
    }
}