<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\Device;
use App\Models\Rooms;
use Carbon\Carbon;

class MonitoringDataController extends Controller
{
    public function getDashboardData()
    {
        try {
            $totalDevices = Device::where('device_type', 'suhu')->count();
            $latestData = Server::selectRaw('device_key, MAX(created_at) as latest_time')->groupBy('device_key')->get();
            $deviceStatus = [];
            $normalCount = $warningCount = $criticalCount = 0;
            
            foreach ($latestData as $latest) {
                $data = Server::where('device_key', $latest->device_key)->where('created_at', $latest->latest_time)->first();
                if ($data) {
                    $room = Rooms::where('device_id', $data->device_key)->first();
                    $status = $data->status_overall ?? 'Unknown';
                    if ($status === 'Normal') $normalCount++;
                    elseif ($status === 'Warning') $warningCount++;
                    else $criticalCount++;
                    
                    $deviceStatus[] = [
                        'device' => $data->device_key, 'ruang_id' => $room ? $room->room_id : null,
                        'ruang_nama' => $room ? $room->room_name : 'Unknown', 'suhu' => $data->suhu,
                        'kelembaban' => $data->kelembapan, 'status_suhu' => $data->status_suhu,
                        'status_overall' => $status, 'waktu' => $data->created_at->format('d/m/Y H:i:s')
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => ['total_devices' => $totalDevices, 'normal_devices' => $normalCount,
                    'warning_devices' => $warningCount, 'critical_devices' => $criticalCount, 'device_status' => $deviceStatus]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data dashboard'], 500);
        }
    }
    
    public function getMonitoringData(Request $request)
    {
        try {
            $query = Server::query();
            if ($request->filled('device') && $request->device !== 'Semua') $query->where('device_key', $request->device);
            if ($request->filled('ruangan') && $request->ruangan !== 'Semua') {
                $room = Rooms::where('room_id', $request->ruangan)->first();
                if ($room && $room->device_id) $query->where('device_key', $room->device_id);
            }
            if ($request->filled('dari_tanggal')) {
                $dariDate = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal);
                if ($request->filled('dari_jam') && $request->dari_jam !== '--:--') {
                    [$hour, $minute] = explode(':', $request->dari_jam);
                    $dariDate->setTime($hour, $minute, 0);
                }
                $query->where('created_at', '>=', $dariDate);
            }
            if ($request->filled('sampai_tanggal')) {
                $sampaiDate = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal);
                if ($request->filled('sampai_jam') && $request->sampai_jam !== '--:--') {
                    [$hour, $minute] = explode(':', $request->sampai_jam);
                    $sampaiDate->setTime($hour, $minute, 59);
                }
                $query->where('created_at', '<=', $sampaiDate);
            }
            
            $perPage = $request->get('per_page', 10);
            $data = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            $formattedData = $data->map(function ($item) {
                $room = Rooms::where('device_id', $item->device_key)->first();
                return [
                    'waktu' => $item->created_at->format('d/m/Y H:i:s'), 'device' => $item->device_key,
                    'ruang' => $room ? $room->room_name : 'Unknown', 'suhu' => $item->suhu,
                    'kelembaban' => $item->kelembapan, 'status_suhu' => $item->status_suhu,
                    'status_kelembaban' => $item->status_kelembapan,
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
}