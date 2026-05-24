<?php

namespace App\Http\Controllers;

use App\Models\Monitoring_Suhu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuhuController extends Controller
{
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
                $ruangan = []; $normalCount = $warningCount = $criticalCount = 0;
            }
        } catch (\Exception $e) {
            $ruangan = []; $normalCount = $warningCount = $criticalCount = 0;
        }
        return view('suhu', compact('ruangan', 'normalCount', 'warningCount', 'criticalCount'));
    }

    public function store(Request $request)
    {
        $request->validate(['suhu' => 'required|numeric', 'kelembaban' => 'required|numeric', 'status_suhu' => 'required|string', 'status_kelembaban' => 'required|string', 'keterangan' => 'nullable|string']);
        
        Monitoring_Suhu::create(['suhu' => $request->suhu, 'kelembaban' => $request->kelembaban, 'status_suhu' => $request->status_suhu, 'status_kelembaban' => $request->status_kelembaban, 'keterangan' => $request->keterangan]);
        
        return response()->json(['status' => 'sukses', 'message' => 'Data monitoring berhasil disimpan ke database'], 201);
    }
}