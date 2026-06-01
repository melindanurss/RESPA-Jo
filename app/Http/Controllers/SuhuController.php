<?php

namespace App\Http\Controllers;

use App\Models\Monitoring_Suhu;
use App\Models\Server;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuhuController extends Controller
{
    /**
     * Menampilkan halaman monitoring suhu DHT22
     */
    public function index()
    {
        try {
            $response = Http::timeout(30)->get('http://magang.rsparumanguharjo.com/api/ruangan');
            if ($response->successful()) {
                $data = $response->json();
                $ruangan = $data['rows'] ?? [];
                $normalCount = $warningCount = $criticalCount = 0;
                foreach($ruangan as $index => $room) {
                    if($index % 3 == 0) $normalCount++;
                    elseif($index % 3 == 1) $warningCount++;
                    else $criticalCount++;
                }
            } else {
                $ruangan = [];
                $normalCount = $warningCount = $criticalCount = 0;
            }
        } catch (\Exception $e) {
            $ruangan = [];
            $normalCount = $warningCount = $criticalCount = 0;
        }
        return view('suhu', compact('ruangan', 'normalCount', 'warningCount', 'criticalCount'));
    }

    /**
     * Menyimpan data dari sensor DHT22 (suhu dan kelembaban saja)
     */
    public function store(Request $request)
    {
        $request->validate([
            'suhu' => 'required|numeric',
            'kelembaban' => 'required|numeric',
            'status_suhu' => 'required|string',
            'status_kelembaban' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);
        
        $data = Monitoring_Suhu::create([
            'suhu' => $request->suhu,
            'kelembaban' => $request->kelembaban,
            'status_suhu' => $request->status_suhu,
            'status_kelembaban' => $request->status_kelembaban,
            'keterangan' => $request->keterangan
        ]);
        
        return response()->json([
            'status' => 'sukses',
            'message' => 'Data monitoring DHT22 berhasil disimpan ke database',
            'data' => $data
        ], 201);
    }

    /**
     * MENYIMPAN DATA DARI SENSOR BME280 (Suhu, Kelembaban, Tekanan Udara)
     * Ini adalah FITUR UTAMA untuk sensor BME280 dengan 3 inovasi
     */
    public function storeBME280(Request $request)
    {
        try {
            // Validasi input untuk BME280
            $validated = $request->validate([
                'device_key' => 'required|string|max:255',
                'suhu' => 'required|numeric|between:-40,85',
                'kelembaban' => 'required|numeric|between:0,100',
                'tekanan_udara' => 'nullable|numeric|between:300,1100',
                'ruang' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string'
            ]);
            
            // Round values untuk konsistensi
            $suhu = round($request->suhu, 1);
            $kelembaban = round($request->kelembaban, 1);
            $tekananUdara = $request->tekanan_udara ? round($request->tekanan_udara, 1) : null;
            
            // Tentukan status berdasarkan threshold
            $statusSuhu = $this->getSuhuStatus($suhu);
            $statusKelembaban = $this->getKelembabanStatus($kelembaban);
            $statusOverall = ($statusSuhu === 'Normal' && $statusKelembaban === 'Normal') ? 'Normal' : 'Warning';
            
            // Cek apakah device terdaftar, jika belum buat otomatis
            $device = Device::where('device_key', $request->device_key)->first();
            if (!$device) {
                Device::create([
                    'device_key' => $request->device_key,
                    'device_type' => 'bme280',
                    'is_active' => true,
                    'name' => $request->device_key,
                    'description' => 'Auto-registered BME280 sensor'
                ]);
                Log::info('Auto-registered new BME280 device: ' . $request->device_key);
            }
            
            // Simpan ke tabel server_monitorings (dengan kolom tekanan_udara)
            $data = Server::create([
                'device_key' => $request->device_key,
                'ruang' => $request->ruang,
                'suhu' => $suhu,
                'kelembapan' => $kelembaban,
                'tekanan_udara' => $tekananUdara,
                'status_suhu' => $statusSuhu,
                'status_kelembaban' => $statusKelembaban,
                'status' => $statusOverall,
                'last_status' => $statusOverall
            ]);
            
            Log::info('Data BME280 berhasil disimpan:', [
                'device' => $request->device_key,
                'suhu' => $suhu,
                'kelembaban' => $kelembaban,
                'tekanan_udara' => $tekananUdara,
                'status' => $statusOverall
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data BME280 berhasil disimpan',
                'data' => [
                    'id' => $data->id,
                    'device_key' => $data->device_key,
                    'suhu' => $suhu,
                    'kelembaban' => $kelembaban,
                    'tekanan_udara' => $tekananUdara,
                    'status' => $statusOverall,
                    'created_at' => $data->created_at->toISOString()
                ]
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi BME280 gagal:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error menyimpan data BME280: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data BME280: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menentukan status suhu berdasarkan threshold
     * Normal: 18°C - 27°C
     * Warning: <18°C atau >27°C
     */
    private function getSuhuStatus($suhu)
    {
        if ($suhu < 18) return "Terlalu Dingin";
        if ($suhu <= 27) return "Normal";
        return "Panas";
    }
    
    /**
     * Menentukan status kelembaban berdasarkan threshold
     * Normal: 30% - 60%
     * Warning: <30% atau >60%
     */
    private function getKelembabanStatus($kelembaban)
    {
        if ($kelembaban < 30) return "Kering";
        if ($kelembaban <= 60) return "Normal";
        return "Lembab";
    }
    
    /**
     * Mendapatkan data dashboard BME280
     */
    public function getBME280Dashboard()
    {
        try {
            $devices = Device::whereIn('device_type', ['bme280', 'suhu'])->where('is_active', true)->get();
            $deviceStatus = [];
            $normalCount = 0;
            $warningCount = 0;
            $totalPressure = 0;
            $pressureCount = 0;
            
            foreach ($devices as $device) {
                $latestData = Server::where('device_key', $device->device_key)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($latestData) {
                    $room = \App\Models\Rooms::where('device_id', $device->device_key)->first();
                    $status = ($latestData->suhu >= 18 && $latestData->suhu <= 27 && 
                              $latestData->kelembapan >= 30 && $latestData->kelembapan <= 60) ? 'Normal' : 'Warning';
                    
                    if ($status === 'Normal') $normalCount++;
                    else $warningCount++;
                    
                    if ($latestData->tekanan_udara) {
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
                    'total_devices' => $devices->count(),
                    'normal_devices' => $normalCount,
                    'warning_devices' => $warningCount,
                    'avg_pressure' => $avgPressure,
                    'device_status' => $deviceStatus
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error BME280 dashboard: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard BME280'
            ], 500);
        }
    }
}