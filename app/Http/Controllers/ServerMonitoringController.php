<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\NotificationService;

class ServerMonitoringController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['device_key' => 'required|string', 'ruang' => 'nullable|string', 'suhu' => 'required|numeric', 'kelembapan' => 'required|numeric']);
        
        $suhu = round($request->suhu, 1);
        $kelembapan = round($request->kelembapan, 1);
        
        $status = 'Normal';
        $warningMessage = "";
        if ($suhu > 28) { $status = 'Warning'; $warningMessage .= "🔥 Suhu Tinggi: {$suhu}°C\n"; }
        if ($kelembapan > 60) { $status = 'Warning'; $warningMessage .= "💧 Kelembapan Tinggi: {$kelembapan}%\n"; }
        if ($warningMessage === "") $warningMessage = "🟢 Semua kondisi normal\n";
        
        $last = Server::where('device_key', $request->device_key)->orderBy('id', 'desc')->first();
        $lastStatus = $last->status ?? 'Normal';
        
        $data = Server::create(['device_key' => $request->device_key, 'ruang' => $request->ruang, 'suhu' => $suhu, 'kelembapan' => $kelembapan, 'status' => $status, 'last_status' => $lastStatus]);
        
        Log::info('Data monitoring berhasil disimpan:', $data->toArray());
        
        if ($lastStatus === $status) {
            return response()->json(['success' => true, 'message' => 'Data tersimpan (status sama → notif tidak dikirim)', 'data' => $data]);
        }
        
        try {
            NotificationService::createTemperatureNotification($request->device_key, $suhu, $kelembapan, $lastStatus, $status);
            Log::info("Notifikasi internal dibuat untuk device: {$request->device_key}");
        } catch (\Exception $e) { Log::error("Gagal membuat notifikasi internal: " . $e->getMessage()); }
        
        $pesan = "📡 *RESPA-Jo - MONITORING SUHU & KELEMBAPAN*\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n🆔 *Device:* `{$request->device_key}`\n📍 *Ruang:* " . ($request->ruang ?: 'Tidak ada') . "\n🌡️ *Suhu:* {$suhu}°C\n💧 *Kelembapan:* {$kelembapan}%\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        if ($status === 'Warning') $pesan .= "⚠️ *STATUS: WARNING*\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" . $warningMessage;
        else $pesan .= "🟢 *STATUS: NORMAL*\nSemua kondisi kembali stabil dan aman.\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n⏳ *Waktu:* " . now()->format('d-m-Y H:i:s');
        
        try { Http::post('https://magang.rsparumanguharjo.com/send-message', ['session' => 'magang', 'to' => env('WA_TUJUAN'), 'message' => $pesan]); } catch (\Exception $e) { Log::error("WhatsApp API error: " . $e->getMessage()); }
        try { Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", ['chat_id' => env('TELEGRAM_CHAT_ID'), 'text' => $pesan, 'parse_mode' => 'Markdown']); } catch (\Exception $e) { Log::error("Telegram API error: " . $e->getMessage()); }
        
        return response()->json(['success' => true, 'message' => 'Data tersimpan & notifikasi terkirim (status berubah)', 'data' => $data]);
    }
}