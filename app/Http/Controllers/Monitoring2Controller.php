<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Server;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Monitoring2Controller extends Controller
{
    public function suhuIndex()
    {
        return view('monitoring.suhu');
    }

    public function getDashboardSummary()
    {
        try {
            $totalDevices = Device::where('device_type', 'suhu')->where('is_active', true)->count();
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
            
            $normalCount = 0;
            $warningCount = 0;
            $criticalCount = 0;
            $totalTemp = 0;
            $deviceWithData = 0;
            
            foreach ($devices as $device) {
                $latestData = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
                if ($latestData) {
                    $deviceWithData++;
                    $totalTemp += $latestData->suhu;
                    $status = $this->determineOverallStatus($latestData->suhu, $latestData->kelembapan);
                    if ($status === 'Normal') $normalCount++;
                    elseif ($status === 'Warning') $warningCount++;
                    else $criticalCount++;
                }
            }
            
            $averageTemp = $deviceWithData > 0 ? round($totalTemp / $deviceWithData, 1) : 0;
            $totalData = Server::count();
            $todayData = Server::whereDate('created_at', Carbon::today())->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'active_devices' => $deviceWithData,
                    'average_temp' => $averageTemp,
                    'normal_devices' => $normalCount,
                    'warning_devices' => $warningCount,
                    'critical_devices' => $criticalCount,
                    'total_data' => $totalData,
                    'today_data' => $todayData,
                    'last_update' => now()->toISOString(),
                    'timestamp' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard summary error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil summary dashboard'], 500);
        }
    }
    
    public function getDevicesForDashboard()
    {
        try {
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get()->map(function ($device) {
                $latestData = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
                $room = Rooms::where('device_id', $device->device_key)->first();
                
                $temperature = 0;
                $humidity = 0;
                $status = 'unknown';
                $statusDisplay = 'Unknown';
                $lastUpdate = null;
                $timeAgo = 'Never';
                
                if ($latestData) {
                    $temperature = $latestData->suhu;
                    $humidity = $latestData->kelembapan;
                    $status = $this->determineOverallStatus($temperature, $humidity);
                    $statusDisplay = $this->getStatusDisplay($status);
                    $lastUpdate = $latestData->created_at->toISOString();
                    $timeAgo = $this->getTimeAgo($latestData->created_at);
                }
                
                return [
                    'id' => $device->id,
                    'device_key' => $device->device_key,
                    'name' => $device->name ?? $device->device_key,
                    'location' => $device->location ?? ($room ? $room->room_name : 'Unknown'),
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'status' => $status,
                    'status_display' => $statusDisplay,
                    'status_class' => $this->getStatusClass($status),
                    'temperature_class' => $this->getTemperatureClass($temperature),
                    'last_update' => $lastUpdate,
                    'time_ago' => $timeAgo,
                    'room_id' => $room ? $room->room_id : null,
                    'room_name' => $room ? $room->room_name : null,
                    'is_active' => $device->is_active
                ];
            });
            
            return response()->json(['success' => true, 'devices' => $devices, 'count' => $devices->count(), 'last_updated' => now()->toISOString()]);
        } catch (\Exception $e) {
            Log::error('Devices for dashboard error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data perangkat'], 500);
        }
    }
    
    public function getRecentAlertsForDashboard()
    {
        try {
            $recentAlerts = Server::where(function($query) {
                    $query->where('suhu', '>', 30)->orWhere('kelembapan', '>', 60)
                          ->orWhere('suhu', '<', 18)->orWhere('kelembapan', '<', 30)
                          ->orWhere(function($q) { $q->where('suhu', '>', 27)->where('suhu', '<=', 30); });
                })->where('created_at', '>=', Carbon::now()->subDay())->orderBy('created_at', 'desc')->limit(10)->get()->map(function ($alert) {
                    $device = Device::where('device_key', $alert->device_key)->first();
                    $room = Rooms::where('device_id', $alert->device_key)->first();
                    
                    $severity = 'info';
                    $message = '';
                    $icon = 'fa-info-circle';
                    
                    if ($alert->suhu > 30) { $severity = 'critical'; $message = "Suhu kritis: {$alert->suhu}°C"; $icon = 'fa-fire'; }
                    elseif ($alert->suhu > 27) { $severity = 'warning'; $message = "Suhu tinggi: {$alert->suhu}°C"; $icon = 'fa-thermometer-three-quarters'; }
                    elseif ($alert->suhu < 18) { $severity = 'warning'; $message = "Suhu rendah: {$alert->suhu}°C"; $icon = 'fa-snowflake'; }
                    elseif ($alert->kelembapan > 60) { $severity = 'warning'; $message = "Kelembaban tinggi: {$alert->kelembapan}%"; $icon = 'fa-tint'; }
                    elseif ($alert->kelembapan < 30) { $severity = 'warning'; $message = "Kelembaban rendah: {$alert->kelembapan}%"; $icon = 'fa-sun'; }
                    
                    return [
                        'id' => $alert->id, 'device' => $device ? $device->name : $alert->device_key,
                        'device_key' => $alert->device_key, 'message' => $message, 'severity' => $severity,
                        'icon' => $icon, 'temperature' => $alert->suhu, 'humidity' => $alert->kelembapan,
                        'timestamp' => $alert->created_at->toISOString(), 'time_ago' => $this->getTimeAgo($alert->created_at),
                        'formatted_time' => $alert->created_at->format('d/m/Y H:i:s'), 'room' => $room ? $room->room_name : 'Unknown'
                    ];
                });
            
            return response()->json(['success' => true, 'alerts' => $recentAlerts, 'count' => $recentAlerts->count(),
                'critical_count' => $recentAlerts->where('severity', 'critical')->count(),
                'warning_count' => $recentAlerts->where('severity', 'warning')->count()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil alert terbaru'], 500);
        }
    }
    
    public function getTemperatureChartForDashboard($period = 'week')
    {
        try {
            $endDate = Carbon::now();
            switch ($period) {
                case 'day': $startDate = $endDate->copy()->subDay(); break;
                case 'week': $startDate = $endDate->copy()->subWeek(); break;
                case 'month': $startDate = $endDate->copy()->subMonth(); break;
                default: $startDate = $endDate->copy()->subWeek();
            }
            
            $groupFormat = $period === 'day' ? 'H:00' : 'Y-m-d';
            $chartData = Server::select(DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as time_group"),
                    DB::raw('MAX(suhu) as max_temp'), DB::raw('AVG(suhu) as avg_temp'), DB::raw('MIN(suhu) as min_temp'))
                ->whereBetween('created_at', [$startDate, $endDate])->groupBy('time_group')->orderBy('time_group')->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'labels' => $chartData->pluck('time_group')->toArray(),
                    'max' => $chartData->pluck('max_temp')->map(fn($v) => floatval($v))->toArray(),
                    'avg' => $chartData->pluck('avg_temp')->map(fn($v) => floatval($v))->toArray(),
                    'min' => $chartData->pluck('min_temp')->map(fn($v) => floatval($v))->toArray(),
                    'period' => $period
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data chart'], 500);
        }
    }
    
    public function getStatusDistributionForDashboard()
    {
        try {
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->get();
            $normalCount = $warningCount = $criticalCount = $offlineCount = 0;
            
            foreach ($devices as $device) {
                $latestData = Server::where('device_key', $device->device_key)->orderBy('created_at', 'desc')->first();
                if ($latestData) {
                    $status = $this->determineOverallStatus($latestData->suhu, $latestData->kelembapan);
                    if ($status === 'Normal') $normalCount++;
                    elseif ($status === 'Warning') $warningCount++;
                    else $criticalCount++;
                } else { $offlineCount++; }
            }
            
            $total = $normalCount + $warningCount + $criticalCount + $offlineCount;
            return response()->json([
                'success' => true,
                'data' => [
                    'normal' => $normalCount, 'warning' => $warningCount, 'critical' => $criticalCount,
                    'offline' => $offlineCount, 'total' => $total,
                    'percentages' => [
                        'normal' => $total > 0 ? round(($normalCount / $total) * 100, 1) : 0,
                        'warning' => $total > 0 ? round(($warningCount / $total) * 100, 1) : 0,
                        'critical' => $total > 0 ? round(($criticalCount / $total) * 100, 1) : 0,
                        'offline' => $total > 0 ? round(($offlineCount / $total) * 100, 1) : 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil distribusi status'], 500);
        }
    }

    public function getDashboardData(Request $request)
    {
        try {
            $totalDevices = Device::where('device_type', 'suhu')->where('is_active', true)->count();
            $totalData = $this->applyFilters(Server::query(), $request)->count();
            
            $latestData = Server::selectRaw('device_key, MAX(created_at) as latest_time')->groupBy('device_key')->get();
            $deviceStatus = [];
            $normalCount = $warningCount = $criticalCount = 0;
            
            foreach ($latestData as $latest) {
                $data = Server::where('device_key', $latest->device_key)->where('created_at', $latest->latest_time)->first();
                if ($data) {
                    $room = Rooms::where('device_id', $data->device_key)->first();
                    $status = $this->determineOverallStatus($data->suhu, $data->kelembapan);
                    if ($status === 'Normal') $normalCount++;
                    elseif ($status === 'Warning') $warningCount++;
                    else $criticalCount++;
                    
                    $deviceStatus[] = [
                        'device' => $data->device_key,
                        'device_name' => Device::where('device_key', $data->device_key)->first()->name ?? $data->device_key,
                        'ruang_id' => $room ? $room->room_id : '-',
                        'ruang_nama' => $room ? $room->room_name : 'Belum ditetapkan',
                        'suhu' => number_format($data->suhu, 1),
                        'kelembaban' => number_format($data->kelembapan, 1),
                        'status_suhu' => $this->getSuhuStatus($data->suhu),
                        'status_kelembaban' => $this->getKelembabanStatus($data->kelembapan),
                        'status_overall' => $status,
                        'waktu' => $data->created_at->format('d/m/Y H:i:s')
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => ['total_devices' => $totalDevices, 'total_data' => $totalData,
                    'normal_devices' => $normalCount, 'warning_devices' => $warningCount, 'critical_devices' => $criticalCount,
                    'device_status' => $deviceStatus]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data dashboard'], 500);
        }
    }

    public function getTotalData(Request $request)
    {
        $cacheKey = 'total_data_' . md5(serialize($request->all()));
        return Cache::remember($cacheKey, 60, function() use ($request) {
            try {
                $query = Server::query();
                $this->applyFilters($query, $request);
                $totalData = $query->count();
                return response()->json(['success' => true, 'total_data' => $totalData]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal menghitung total data'], 500);
            }
        });
    }

    public function getMonitoringData(Request $request)
    {
        try {
            $query = Server::query();
            $this->applyFilters($query, $request);
            $perPage = $request->get('per_page', 10);
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            $formattedData = $data->map(function ($item) {
                $room = Rooms::where('device_id', $item->device_key)->first();
                $device = Device::where('device_key', $item->device_key)->first();
                return [
                    'waktu' => $item->created_at->format('d/m/Y H:i:s'),
                    'device' => $item->device_key,
                    'device_name' => $device->name ?? $item->device_key,
                    'ruang' => $room ? $room->room_name : 'Unknown',
                    'suhu' => number_format($item->suhu, 1),
                    'kelembaban' => number_format($item->kelembapan, 1),
                    'status_suhu' => $this->getSuhuStatus($item->suhu),
                    'status_kelembaban' => $this->getKelembabanStatus($item->kelembapan),
                ];
            });
            
            return response()->json([
                'success' => true, 'data' => $formattedData, 'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(), 'total' => $data->total(), 'per_page' => $data->perPage()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data monitoring'], 500);
        }
    }

    public function exportData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'export_range' => 'required|in:today,yesterday,week,month,last_month,custom,all',
                'custom_start' => 'nullable|date_format:d/m/Y',
                'custom_end' => 'nullable|date_format:d/m/Y'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal'], 422);
            }

            $query = Server::query();
            $this->applyFilters($query, $request);
            $this->applyExportDateRange($query, $request->export_range, $request);
            
            $totalData = $query->count();
            if ($totalData === 0) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data yang ditemukan'], 404);
            }
            
            $filename = $this->generateExportFilename($request->export_range, $request);
            $headers = ['Content-Type' => 'text/csv; charset=utf-8', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];
            
            return Response::stream(function() use ($query) {
                $file = fopen('php://output', 'w');
                fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                
                $headers = ['No', 'Waktu', 'Device Key', 'Nama Device', 'ID Ruang', 'Nama Ruang', 'Suhu (°C)', 'Kelembaban (%)', 'Status Suhu', 'Status Kelembaban', 'Status Overall'];
                fputcsv($file, $headers, ';');
                
                $counter = 1;
                $query->orderBy('created_at', 'desc')->chunk(5000, function($chunk) use ($file, &$counter) {
                    foreach ($chunk as $item) {
                        $room = Rooms::where('device_id', $item->device_key)->first();
                        $device = Device::where('device_key', $item->device_key)->first();
                        $statusSuhu = $this->getSuhuStatus($item->suhu);
                        $statusKelembaban = $this->getKelembabanStatus($item->kelembapan);
                        $statusOverall = $this->determineOverallStatus($item->suhu, $item->kelembapan);
                        
                        fputcsv($file, [
                            $counter++, $item->created_at->format('d/m/Y H:i:s'), $item->device_key,
                            $device->name ?? $item->device_key, $room ? $room->room_id : '-',
                            $room ? $room->room_name : 'Unknown', number_format($item->suhu, 2, ',', '.'),
                            number_format($item->kelembapan, 2, ',', '.'), $statusSuhu, $statusKelembaban, $statusOverall
                        ], ';');
                        
                        if (ob_get_level() > 0) ob_flush(); flush();
                    }
                });
                fclose($file);
            }, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengekspor data'], 500);
        }
    }

    public function getExportStats()
    {
        try {
            $now = Carbon::now();
            $stats = [
                'today' => ['label' => 'Hari Ini', 'count' => Server::whereDate('created_at', $now->toDateString())->count()],
                'yesterday' => ['label' => 'Kemarin', 'count' => Server::whereDate('created_at', $now->copy()->subDay()->toDateString())->count()],
                'week' => ['label' => '7 Hari Terakhir', 'count' => Server::where('created_at', '>=', $now->copy()->startOfWeek())->count()],
                'month' => ['label' => 'Bulan Ini', 'count' => Server::where('created_at', '>=', $now->copy()->startOfMonth())->count()],
                'last_month' => ['label' => 'Bulan Lalu', 'count' => Server::whereBetween('created_at', [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()])->count()],
                'all' => ['label' => 'Semua Data', 'count' => Server::count()]
            ];
            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik ekspor'], 500);
        }
    }

    public function getDevicesFilter()
    {
        try {
            $devices = Device::where('device_type', 'suhu')->where('is_active', true)->orderBy('device_key')->get(['device_key', 'name']);
            return response()->json(['success' => true, 'devices' => $devices]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data device'], 500);
        }
    }

    public function getRoomsFilter()
    {
        try {
            $rooms = Rooms::orderBy('room_name')->get(['room_id', 'room_name']);
            return response()->json(['success' => true, 'rooms' => $rooms]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data ruangan'], 500);
        }
    }

    private function getSuhuStatus($suhu)
    {
        if ($suhu < 18) return "Terlalu Dingin";
        if ($suhu <= 27) return "Normal";
        return "Panas";
    }

    private function getKelembabanStatus($kelembaban)
    {
        if ($kelembaban < 30) return "Kering";
        if ($kelembaban <= 60) return "Normal";
        return "Lembab";
    }

    private function determineOverallStatus($suhu, $kelembaban)
    {
        $statusSuhu = $this->getSuhuStatus($suhu);
        $statusKelembaban = $this->getKelembabanStatus($kelembaban);
        if ($statusSuhu === 'Normal' && $statusKelembaban === 'Normal') return 'Normal';
        if ($suhu > 30 || $kelembaban > 60 || $suhu < 18 || $kelembaban < 30) return 'Critical';
        return 'Warning';
    }
    
    private function getStatusDisplay($status)
    {
        switch($status) {
            case 'Normal': return 'Normal';
            case 'Warning': return 'Warning';
            case 'Critical': return 'Kritis';
            default: return 'Unknown';
        }
    }
    
    private function getStatusClass($status)
    {
        switch($status) {
            case 'Normal': return 'status-normal';
            case 'Warning': return 'status-warning';
            case 'Critical': return 'status-critical';
            default: return 'status-unknown';
        }
    }
    
    private function getTemperatureClass($temperature)
    {
        if ($temperature <= 27) return 'normal';
        if ($temperature <= 30) return 'warning';
        return 'critical';
    }
    
    private function getTimeAgo($timestamp)
    {
        $now = Carbon::now();
        $time = Carbon::parse($timestamp);
        $diff = $now->diffInSeconds($time);
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        return floor($diff / 86400) . ' hari lalu';
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('device') && $request->device !== 'Semua') {
            $query->where('device_key', $request->device);
        }
        if ($request->filled('ruangan') && $request->ruangan !== 'Semua') {
            $room = Rooms::where('room_id', $request->ruangan)->first();
            if ($room && $room->device_id) $query->where('device_key', $room->device_id);
        }
        if ($request->filled('status_suhu') && $request->status_suhu !== 'Semua') {
            switch($request->status_suhu) {
                case 'Normal': $query->whereBetween('suhu', [18, 27]); break;
                case 'Terlalu Dingin': $query->where('suhu', '<', 18); break;
                case 'Panas': $query->where('suhu', '>', 27); break;
            }
        }
        if ($request->filled('status_kelembaban') && $request->status_kelembaban !== 'Semua') {
            switch($request->status_kelembaban) {
                case 'Normal': $query->whereBetween('kelembapan', [30, 60]); break;
                case 'Lembab': $query->where('kelembapan', '>', 60); break;
                case 'Kering': $query->where('kelembapan', '<', 30); break;
            }
        }
        if ($request->filled('dari_tanggal') && $request->dari_tanggal) {
            $dariDate = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal);
            if ($request->filled('dari_jam') && $request->dari_jam) {
                [$hour, $minute] = explode(':', $request->dari_jam);
                $dariDate->setTime($hour, $minute, 0);
            }
            $query->where('created_at', '>=', $dariDate);
        }
        if ($request->filled('sampai_tanggal') && $request->sampai_tanggal) {
            $sampaiDate = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal);
            if ($request->filled('sampai_jam') && $request->sampai_jam) {
                [$hour, $minute] = explode(':', $request->sampai_jam);
                $sampaiDate->setTime($hour, $minute, 59);
            }
            $query->where('created_at', '<=', $sampaiDate);
        }
    }

    private function applyExportDateRange($query, $exportRange, $request)
    {
        $now = Carbon::now();
        switch ($exportRange) {
            case 'today': $query->whereDate('created_at', $now->toDateString()); break;
            case 'yesterday': $query->whereDate('created_at', $now->copy()->subDay()->toDateString()); break;
            case 'week': $query->where('created_at', '>=', $now->copy()->startOfWeek()); break;
            case 'month': $query->where('created_at', '>=', $now->copy()->startOfMonth()); break;
            case 'last_month': $query->whereBetween('created_at', [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()]); break;
            case 'custom':
                if ($request->filled('custom_start')) $query->where('created_at', '>=', Carbon::createFromFormat('d/m/Y', $request->custom_start)->startOfDay());
                if ($request->filled('custom_end')) $query->where('created_at', '<=', Carbon::createFromFormat('d/m/Y', $request->custom_end)->endOfDay());
                break;
        }
    }

    private function generateExportFilename($exportRange, $request)
    {
        $timestamp = Carbon::now()->format('Ymd-His');
        $baseName = 'monitoring-suhu';
        switch ($exportRange) {
            case 'today': return "{$baseName}-hari-ini-{$timestamp}.csv";
            case 'yesterday': return "{$baseName}-kemarin-{$timestamp}.csv";
            case 'week': return "{$baseName}-minggu-ini-{$timestamp}.csv";
            case 'month': return "{$baseName}-bulan-ini-{$timestamp}.csv";
            case 'last_month': return "{$baseName}-bulan-lalu-{$timestamp}.csv";
            case 'custom': return "{$baseName}-custom-{$timestamp}.csv";
            default: return "{$baseName}-semua-data-{$timestamp}.csv";
        }
    }
}