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

class BME280Controller extends Controller
{
    /**
     * Menampilkan halaman monitoring BME280
     * Menggunakan view monitoring.suhu2 (yang sudah dimodifikasi untuk BME280)
     */
    public function index()
    {
        // Menggunakan view suhu2.blade.php yang sudah dimodifikasi untuk BME280
        return view('monitoring.suhu2');
    }

    /**
     * Mendapatkan data dashboard untuk BME280
     * Menampilkan: total device, status normal/warning, rata-rata tekanan udara
     */
    public function getDashboardData(Request $request)
    {
        try {
            // Ambil semua device tipe bme280 atau suhu yang aktif
            $devices = Device::whereIn('device_type', ['bme280', 'suhu'])
                ->where('is_active', true)
                ->get();
            
            $totalDevices = $devices->count();
            $normalCount = 0;
            $warningCount = 0;
            $totalPressure = 0;
            $pressureCount = 0;
            $deviceStatus = [];
            
            foreach ($devices as $device) {
                // Ambil data terbaru dari setiap device
                $latestData = Server::where('device_key', $device->device_key)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($latestData) {
                    $room = Rooms::where('device_id', $device->device_key)->first();
                    
                    // Tentukan status (hanya Normal atau Warning)
                    $isNormal = ($latestData->suhu >= 18 && $latestData->suhu <= 27 && 
                                 $latestData->kelembapan >= 30 && $latestData->kelembapan <= 60);
                    $status = $isNormal ? 'Normal' : 'Warning';
                    
                    if ($status === 'Normal') {
                        $normalCount++;
                    } else {
                        $warningCount++;
                    }
                    
                    // Hitung rata-rata tekanan udara
                    if (!empty($latestData->tekanan_udara)) {
                        $totalPressure += $latestData->tekanan_udara;
                        $pressureCount++;
                    }
                    
                    $deviceStatus[] = [
                        'device' => $latestData->device_key,
                        'device_name' => $device->name ?? $latestData->device_key,
                        'ruang_id' => $room ? $room->room_id : '-',
                        'ruang_nama' => $room ? $room->room_name : 'Belum ditetapkan',
                        'suhu' => number_format($latestData->suhu, 1),
                        'kelembaban' => number_format($latestData->kelembapan, 1),
                        'tekanan_udara' => $latestData->tekanan_udara ? number_format($latestData->tekanan_udara, 1) : null,
                        'status_overall' => $status,
                        'waktu' => $latestData->created_at->format('d/m/Y H:i:s')
                    ];
                }
            }
            
            $avgPressure = $pressureCount > 0 ? round($totalPressure / $pressureCount, 1) : 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'normal_devices' => $normalCount,
                    'warning_devices' => $warningCount,
                    'avg_pressure' => $avgPressure,
                    'device_status' => $deviceStatus,
                    'last_updated' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('BME280 Dashboard error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan data monitoring dengan filter dan pagination
     */
    public function getMonitoringData(Request $request)
    {
        try {
            $query = Server::query();
            
            // Terapkan filter
            $this->applyFilters($query, $request);
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Format data untuk ditampilkan
            $formattedData = $data->map(function ($item) {
                $room = Rooms::where('device_id', $item->device_key)->first();
                $device = Device::where('device_key', $item->device_key)->first();
                
                // Tentukan status (hanya Normal atau Warning)
                $isNormalSuhu = ($item->suhu >= 18 && $item->suhu <= 27);
                $isNormalKelembaban = ($item->kelembapan >= 30 && $item->kelembapan <= 60);
                
                return [
                    'waktu' => $item->created_at->format('d/m/Y H:i:s'),
                    'device' => $item->device_key,
                    'device_name' => $device->name ?? $item->device_key,
                    'ruang' => $room ? $room->room_name : 'Unknown',
                    'suhu' => number_format($item->suhu, 1),
                    'kelembaban' => number_format($item->kelembapan, 1),
                    'tekanan_udara' => $item->tekanan_udara ? number_format($item->tekanan_udara, 1) : null,
                    'status_suhu' => $isNormalSuhu ? 'Normal' : 'Warning',
                    'status_kelembaban' => $isNormalKelembaban ? 'Normal' : 'Warning',
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
                'per_page' => $data->perPage()
            ]);
            
        } catch (\Exception $e) {
            Log::error('BME280 Monitoring error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data monitoring: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ekspor data ke CSV
     */
    public function exportData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'export_range' => 'required|in:today,yesterday,week,month,last_month,custom,all',
                'custom_start' => 'nullable|date_format:d/m/Y',
                'custom_end' => 'nullable|date_format:d/m/Y'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Server::query();
            $this->applyFilters($query, $request);
            $this->applyExportDateRange($query, $request->export_range, $request);
            
            $totalData = $query->count();
            if ($totalData === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang ditemukan untuk diekspor'
                ], 404);
            }
            
            // Generate filename
            $filename = $this->generateExportFilename($request->export_range, $request);
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ];
            
            return Response::stream(function() use ($query) {
                $file = fopen('php://output', 'w');
                // Add BOM for UTF-8
                fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                
                // Header CSV
                $headers = [
                    'No', 'Waktu', 'Device Key', 'Nama Device', 'ID Ruang', 'Nama Ruang',
                    'Suhu (°C)', 'Kelembaban (%)', 'Tekanan Udara (hPa)',
                    'Status Suhu', 'Status Kelembaban', 'Status Overall'
                ];
                fputcsv($file, $headers, ';');
                
                $counter = 1;
                $query->orderBy('created_at', 'desc')->chunk(5000, function($chunk) use ($file, &$counter) {
                    foreach ($chunk as $item) {
                        $room = Rooms::where('device_id', $item->device_key)->first();
                        $device = Device::where('device_key', $item->device_key)->first();
                        
                        $isNormalSuhu = ($item->suhu >= 18 && $item->suhu <= 27);
                        $isNormalKelembaban = ($item->kelembapan >= 30 && $item->kelembapan <= 60);
                        
                        $statusSuhu = $isNormalSuhu ? 'Normal' : 'Warning';
                        $statusKelembaban = $isNormalKelembaban ? 'Normal' : 'Warning';
                        $statusOverall = ($statusSuhu === 'Normal' && $statusKelembaban === 'Normal') ? 'Normal' : 'Warning';
                        
                        fputcsv($file, [
                            $counter++,
                            $item->created_at->format('d/m/Y H:i:s'),
                            $item->device_key,
                            $device->name ?? $item->device_key,
                            $room ? $room->room_id : '-',
                            $room ? $room->room_name : 'Unknown',
                            number_format($item->suhu, 2, ',', '.'),
                            number_format($item->kelembapan, 2, ',', '.'),
                            $item->tekanan_udara ? number_format($item->tekanan_udara, 2, ',', '.') : '-',
                            $statusSuhu,
                            $statusKelembaban,
                            $statusOverall
                        ], ';');
                        
                        if (ob_get_level() > 0) ob_flush();
                        flush();
                    }
                });
                fclose($file);
            }, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('BME280 Export error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik untuk modal export
     */
    public function getExportStats()
    {
        try {
            $now = Carbon::now();
            $stats = [
                'today' => [
                    'label' => 'Hari Ini',
                    'count' => Server::whereDate('created_at', $now->toDateString())->count()
                ],
                'yesterday' => [
                    'label' => 'Kemarin',
                    'count' => Server::whereDate('created_at', $now->copy()->subDay()->toDateString())->count()
                ],
                'week' => [
                    'label' => '7 Hari Terakhir',
                    'count' => Server::where('created_at', '>=', $now->copy()->startOfWeek())->count()
                ],
                'month' => [
                    'label' => 'Bulan Ini',
                    'count' => Server::where('created_at', '>=', $now->copy()->startOfMonth())->count()
                ],
                'last_month' => [
                    'label' => 'Bulan Lalu',
                    'count' => Server::whereBetween('created_at', [
                        $now->copy()->subMonth()->startOfMonth(),
                        $now->copy()->subMonth()->endOfMonth()
                    ])->count()
                ],
                'all' => [
                    'label' => 'Semua Data',
                    'count' => Server::count()
                ]
            ];
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('BME280 Export stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik ekspor'
            ], 500);
        }
    }

    /**
     * Mendapatkan daftar device untuk dropdown filter
     */
    public function getDevicesFilter()
    {
        try {
            $devices = Device::whereIn('device_type', ['bme280', 'suhu'])
                ->where('is_active', true)
                ->orderBy('device_key')
                ->get(['device_key', 'name', 'device_type']);
            
            return response()->json([
                'success' => true,
                'devices' => $devices
            ]);
            
        } catch (\Exception $e) {
            Log::error('BME280 Devices filter error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data device'
            ], 500);
        }
    }

    /**
     * Apply filters ke query
     */
    private function applyFilters($query, $request)
    {
        // Filter device
        if ($request->filled('device') && $request->device !== 'Semua') {
            $query->where('device_key', $request->device);
        }
        
        // Filter ruangan
        if ($request->filled('ruangan') && $request->ruangan !== 'Semua') {
            $room = Rooms::where('room_id', $request->ruangan)->first();
            if ($room && $room->device_id) {
                $query->where('device_key', $room->device_id);
            }
        }
        
        // Filter status suhu (Normal/Warning)
        if ($request->filled('status_suhu') && $request->status_suhu !== 'Semua') {
            if ($request->status_suhu === 'Normal') {
                $query->whereBetween('suhu', [18, 27]);
            } else {
                $query->where(function($q) {
                    $q->where('suhu', '<', 18)->orWhere('suhu', '>', 27);
                });
            }
        }
        
        // Filter status kelembaban (Normal/Warning)
        if ($request->filled('status_kelembaban') && $request->status_kelembaban !== 'Semua') {
            if ($request->status_kelembaban === 'Normal') {
                $query->whereBetween('kelembapan', [30, 60]);
            } else {
                $query->where(function($q) {
                    $q->where('kelembapan', '<', 30)->orWhere('kelembapan', '>', 60);
                });
            }
        }
        
        // Filter tanggal dari
        if ($request->filled('dari_tanggal') && $request->dari_tanggal) {
            $dariDate = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal);
            if ($request->filled('dari_jam') && $request->dari_jam) {
                [$hour, $minute] = explode(':', $request->dari_jam);
                $dariDate->setTime((int)$hour, (int)$minute, 0);
            } else {
                $dariDate->setTime(0, 0, 0);
            }
            $query->where('created_at', '>=', $dariDate);
        }
        
        // Filter tanggal sampai
        if ($request->filled('sampai_tanggal') && $request->sampai_tanggal) {
            $sampaiDate = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal);
            if ($request->filled('sampai_jam') && $request->sampai_jam) {
                [$hour, $minute] = explode(':', $request->sampai_jam);
                $sampaiDate->setTime((int)$hour, (int)$minute, 59);
            } else {
                $sampaiDate->setTime(23, 59, 59);
            }
            $query->where('created_at', '<=', $sampaiDate);
        }
    }

    /**
     * Apply date range untuk export
     */
    private function applyExportDateRange($query, $exportRange, $request)
    {
        $now = Carbon::now();
        
        switch ($exportRange) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created_at', $now->copy()->subDay()->toDateString());
                break;
            case 'week':
                $query->where('created_at', '>=', $now->copy()->startOfWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', $now->copy()->startOfMonth());
                break;
            case 'last_month':
                $query->whereBetween('created_at', [
                    $now->copy()->subMonth()->startOfMonth(),
                    $now->copy()->subMonth()->endOfMonth()
                ]);
                break;
            case 'custom':
                if ($request->filled('custom_start')) {
                    $query->where('created_at', '>=', 
                        Carbon::createFromFormat('d/m/Y', $request->custom_start)->startOfDay());
                }
                if ($request->filled('custom_end')) {
                    $query->where('created_at', '<=', 
                        Carbon::createFromFormat('d/m/Y', $request->custom_end)->endOfDay());
                }
                break;
        }
    }

    /**
     * Generate filename untuk export
     */
    private function generateExportFilename($exportRange, $request)
    {
        $timestamp = Carbon::now()->format('Ymd-His');
        $baseName = 'bme280-monitoring';
        
        switch ($exportRange) {
            case 'today':
                return "{$baseName}-hari-ini-{$timestamp}.csv";
            case 'yesterday':
                return "{$baseName}-kemarin-{$timestamp}.csv";
            case 'week':
                return "{$baseName}-minggu-ini-{$timestamp}.csv";
            case 'month':
                return "{$baseName}-bulan-ini-{$timestamp}.csv";
            case 'last_month':
                return "{$baseName}-bulan-lalu-{$timestamp}.csv";
            case 'custom':
                return "{$baseName}-custom-{$timestamp}.csv";
            default:
                return "{$baseName}-semua-data-{$timestamp}.csv";
        }
    }
}