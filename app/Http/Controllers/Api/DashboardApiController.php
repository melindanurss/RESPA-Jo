<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Device;
use App\Models\Infus;
use App\Models\Rooms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    /**
     * Get real-time temperature data
     */
    public function temperatureRealTime(Request $request)
    {
        try {
            $range = $request->get('range', '1h');
            $device = $request->get('device', 'all');
            
            // Set time range
            $endTime = Carbon::now();
            switch ($range) {
                case '6h':
                    $startTime = Carbon::now()->subHours(6);
                    break;
                case '24h':
                    $startTime = Carbon::now()->subHours(24);
                    break;
                default:
                    $startTime = Carbon::now()->subHour();
            }
            
            // Build query
            $query = Server::whereBetween('created_at', [$startTime, $endTime]);
            
            if ($device !== 'all') {
                $query->where('device_key', $device);
            }
            
            // Get data grouped by time intervals
            $data = $query->orderBy('created_at', 'asc')
                ->get()
                ->groupBy(function($item) use ($range) {
                    if ($range === '1h') {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:i');
                    } elseif ($range === '6h') {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:00');
                    } else {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:00');
                    }
                });
            
            $labels = [];
            $values = [];
            
            foreach ($data as $time => $items) {
                if ($range === '1h') {
                    $labels[] = Carbon::parse($time)->format('H:i');
                } else {
                    $labels[] = Carbon::parse($time)->format('H:00');
                }
                $values[] = round($items->avg('suhu'), 1);
            }
            
            // Get current temperature
            $current = $query->latest()->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'values' => $values,
                    'current' => $current ? round($current->suhu, 1) : 0,
                    'min' => count($values) > 0 ? min($values) : 0,
                    'max' => count($values) > 0 ? max($values) : 0,
                    'avg' => count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0,
                    'range' => $range
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get real-time humidity data
     */
    public function humidityRealTime(Request $request)
    {
        try {
            $range = $request->get('range', '1h');
            $device = $request->get('device', 'all');
            
            // Set time range
            $endTime = Carbon::now();
            switch ($range) {
                case '6h':
                    $startTime = Carbon::now()->subHours(6);
                    break;
                case '24h':
                    $startTime = Carbon::now()->subHours(24);
                    break;
                default:
                    $startTime = Carbon::now()->subHour();
            }
            
            // Build query
            $query = Server::whereBetween('created_at', [$startTime, $endTime]);
            
            if ($device !== 'all') {
                $query->where('device_key', $device);
            }
            
            // Get data grouped by time intervals
            $data = $query->orderBy('created_at', 'asc')
                ->get()
                ->groupBy(function($item) use ($range) {
                    if ($range === '1h') {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:i');
                    } elseif ($range === '6h') {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:00');
                    } else {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:00');
                    }
                });
            
            $labels = [];
            $values = [];
            
            foreach ($data as $time => $items) {
                if ($range === '1h') {
                    $labels[] = Carbon::parse($time)->format('H:i');
                } else {
                    $labels[] = Carbon::parse($time)->format('H:00');
                }
                $values[] = round($items->avg('kelembapan'), 1);
            }
            
            // Get current humidity
            $current = $query->latest()->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'values' => $values,
                    'current' => $current ? round($current->kelembapan, 1) : 0,
                    'min' => count($values) > 0 ? min($values) : 0,
                    'max' => count($values) > 0 ? max($values) : 0,
                    'avg' => count($values) > 0 ? round(array_sum($values) / count($values), 1) : 0,
                    'range' => $range
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get device status for chart
     */
    public function deviceStatus(Request $request)
    {
        try {
            $totalDevices = Device::count();
            $activeDevices = Device::where('is_active', true)->count();
            
            // Count warning devices (temperature > 28 in last 24h)
            $warningDevices = Device::where('device_type', 'suhu')
                ->where('is_active', true)
                ->whereHas('serverData', function($query) {
                    $query->where('suhu', '>', 28)
                          ->where('created_at', '>=', Carbon::now()->subHours(24));
                })
                ->count();
            
            $inactiveDevices = $totalDevices - $activeDevices;
            $normalDevices = $activeDevices - $warningDevices;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => ['Normal', 'Warning', 'Nonaktif'],
                    'values' => [$normalDevices, $warningDevices, $inactiveDevices],
                    'colors' => ['#2ecc71', '#f39c12', '#e74c3c'],
                    'total' => $totalDevices
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get temperature chart data
     */
    public function temperatureChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'week');
            
            switch ($period) {
                case 'day':
                    $days = 1;
                    $groupFormat = 'H:00';
                    $labelFormat = 'H:i';
                    break;
                case 'week':
                    $days = 7;
                    $groupFormat = 'Y-m-d';
                    $labelFormat = 'D';
                    break;
                case 'month':
                    $days = 30;
                    $groupFormat = 'Y-m-d';
                    $labelFormat = 'd M';
                    break;
                default:
                    $days = 7;
                    $groupFormat = 'Y-m-d';
                    $labelFormat = 'D';
            }
            
            $startDate = Carbon::now()->subDays($days);
            $endDate = Carbon::now();
            
            $data = Server::whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('MAX(suhu) as max_temp'),
                    DB::raw('AVG(suhu) as avg_temp'),
                    DB::raw('MIN(suhu) as min_temp')
                )
                ->groupBy('date', 'hour')
                ->orderBy('date', 'asc')
                ->orderBy('hour', 'asc')
                ->get();
            
            $labels = [];
            $max = [];
            $avg = [];
            $min = [];
            
            foreach ($data as $item) {
                $time = Carbon::parse($item->date . ' ' . $item->hour . ':00:00');
                
                if ($period === 'day') {
                    $labels[] = $time->format($labelFormat);
                } else {
                    $labels[] = $time->format($labelFormat);
                }
                
                $max[] = round($item->max_temp, 1);
                $avg[] = round($item->avg_temp, 1);
                $min[] = round($item->min_temp, 1);
            }
            
            // If no data, provide sample data
            if (empty($labels)) {
                for ($i = 0; $i < 7; $i++) {
                    $labels[] = Carbon::now()->subDays(6 - $i)->format('D');
                    $max[] = rand(24, 30);
                    $avg[] = rand(22, 28);
                    $min[] = rand(20, 24);
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'max' => $max,
                    'avg' => $avg,
                    'min' => $min,
                    'period' => $period
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        try {
            $totalDevices = Device::count();
            $activeDevices = Device::where('is_active', true)->count();
            $roomMonitorings = Rooms::whereNotNull('device_id')->count();
            
            // Get recent alerts
            $recentAlerts = Server::where('status', 'Warning')
                ->orWhere('created_at', '>=', Carbon::now()->subHours(1))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Get system status
            $temperatureAlerts = Server::where('suhu', '>', 28)
                ->where('created_at', '>=', Carbon::now()->subHours(1))
                ->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'active_devices' => $activeDevices,
                    'room_monitorings' => $roomMonitorings,
                    'temperature_alerts' => $temperatureAlerts,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}