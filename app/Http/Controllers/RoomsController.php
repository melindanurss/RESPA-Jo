<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{
    public function index()
    {
        try {
            $rooms = Rooms::all();
            return response()->json(['ok' => true, 'data' => $rooms]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal mengambil data ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(['room_id' => 'required|string|unique:rooms,room_id', 'room_name' => 'required|string', 'device_id' => 'required|string|exists:devices,device_key']);
            DB::beginTransaction();
            
            $device = Device::where('device_key', $validated['device_id'])->first();
            $room = Rooms::create(['room_id' => $validated['room_id'], 'room_name' => $validated['room_name'], 'device_id' => $validated['device_id'], 'status' => 'active']);
            $device->update(['is_active' => true, 'last_activity' => $validated['room_id'], 'last_active' => now()]);
            
            DB::commit();
            return response()->json(['ok' => true, 'message' => 'Ruangan berhasil ditambahkan!', 'data' => $room, 'device' => $device->fresh()], 201);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal menambahkan ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $room = Rooms::find($id);
            if (!$room) return response()->json(['ok' => false, 'error' => 'Ruangan tidak ditemukan'], 404);
            return response()->json(['ok' => true, 'data' => $room]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal mengambil data ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $room = Rooms::find($id);
            if (!$room) return response()->json(['ok' => false, 'error' => 'Ruangan tidak ditemukan'], 404);
            
            $validator = Validator::make($request->all(), [
                'room_id' => 'sometimes|required|string|unique:rooms,room_id,' . $id,
                'room_name' => 'sometimes|required|string', 'device_id' => 'nullable|string', 'status' => 'sometimes|string'
            ]);
            
            if ($validator->fails()) return response()->json(['ok' => false, 'error' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            
            $room->update($validator->validated());
            return response()->json(['ok' => true, 'message' => 'Ruangan berhasil diperbarui', 'data' => $room]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal memperbarui ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $room = Rooms::find($id);
            if (!$room) return response()->json(['ok' => false, 'error' => 'Ruangan tidak ditemukan'], 404);
            $room->delete();
            return response()->json(['ok' => true, 'message' => 'Ruangan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal menghapus ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function list()
    {
        $rooms = [
            ["id_ruang" => "FAR", "nama_ruang" => "Farmasi"], ["id_ruang" => "WH", "nama_ruang" => "Gudang Farmasi"],
            ["id_ruang" => "ICU", "nama_ruang" => "ICU"], ["id_ruang" => "IGD", "nama_ruang" => "IGD"],
            ["id_ruang" => "KSR", "nama_ruang" => "Kasir"], ["id_ruang" => "LAB", "nama_ruang" => "Laboratorium"],
            ["id_ruang" => "ASMA", "nama_ruang" => "Poli Asma"], ["id_ruang" => "GIGI", "nama_ruang" => "Poli Gigi"],
            ["id_ruang" => "GIZI", "nama_ruang" => "Poli Gizi"], ["id_ruang" => "JAN", "nama_ruang" => "Poli Jantung"],
            ["id_ruang" => "OG", "nama_ruang" => "Poli Kandungan"], ["id_ruang" => "PARU", "nama_ruang" => "Poli Paru"],
            ["id_ruang" => "PD", "nama_ruang" => "Poli Penyakit Dalam"], ["id_ruang" => "SARAF", "nama_ruang" => "Poli Saraf"],
            ["id_ruang" => "RAD", "nama_ruang" => "Radiologi"], ["id_ruang" => "RJL", "nama_ruang" => "Rajal"],
            ["id_ruang" => "RNP", "nama_ruang" => "Ranap"], ["id_ruang" => "REHAB", "nama_ruang" => "Rehab Medik"],
            ["id_ruang" => "RM", "nama_ruang" => "Rekam Medis"], ["id_ruang" => "AKRE", "nama_ruang" => "Ruang Akreditasi"],
            ["id_ruang" => "DIREK", "nama_ruang" => "Ruang Direksi"], ["id_ruang" => "KEU", "nama_ruang" => "Ruang Keuangan"],
            ["id_ruang" => "PPI", "nama_ruang" => "Ruang PPI"], ["id_ruang" => "MEET", "nama_ruang" => "Ruang Pertemuan"],
            ["id_ruang" => "UMPEG", "nama_ruang" => "Ruang Umum dan Kepegawaian"], ["id_ruang" => "SVR", "nama_ruang" => "Server"]
        ];
        return response()->json(['ok' => true, 'rows' => $rooms]);
    }

    public function refresh()
    {
        try {
            $response = Http::timeout(30)->get('http://magang.rsparumanguharjo.com/api/ruangan');
            if (!$response->successful()) throw new \Exception('Gagal mengambil data dari API eksternal');
            
            $externalData = $response->json();
            $processedData = $this->processExternalData($externalData);
            return response()->json(['ok' => true, 'message' => 'Data ruangan berhasil di-refresh', 'data' => $processedData, 'count' => count($processedData)]);
        } catch (\Exception $e) {
            Log::error('Error refreshing room data: ' . $e->getMessage());
            return response()->json(['ok' => false, 'error' => 'Gagal refresh data: ' . $e->getMessage()], 500);
        }
    }

    private function processExternalData($externalData)
    {
        $processed = [];
        if (isset($externalData['data']) && is_array($externalData['data'])) {
            foreach ($externalData['data'] as $roomData) {
                $room = Rooms::updateOrCreate(['room_id' => $roomData['id_ruang'] ?? $roomData['room_id'] ?? null], [
                    'room_name' => $roomData['nama_ruang'] ?? $roomData['room_name'] ?? 'Unknown',
                    'current_temperature' => $roomData['suhu'] ?? $roomData['temperature'] ?? null,
                    'current_humidity' => $roomData['kelembaban'] ?? $roomData['humidity'] ?? null,
                    'last_update' => now()
                ]);
                $processed[] = $room;
            }
        }
        return $processed;
    }

    public function statistics()
    {
        try {
            $totalRooms = Rooms::count();
            $activeRooms = Rooms::where('status', 'active')->count();
            $roomsWithDevices = Rooms::whereNotNull('device_id')->count();
            $tempStats = Rooms::whereNotNull('current_temperature')->selectRaw('AVG(current_temperature) as avg_temp, MIN(current_temperature) as min_temp, MAX(current_temperature) as max_temp')->first();
            
            return response()->json(['ok' => true, 'data' => ['total_rooms' => $totalRooms, 'active_rooms' => $activeRooms, 'rooms_with_devices' => $roomsWithDevices, 'temperature_stats' => $tempStats]]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal mengambil statistik: ' . $e->getMessage()], 500);
        }
    }
}