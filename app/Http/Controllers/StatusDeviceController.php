<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatusDeviceService;

class StatusDeviceController extends Controller
{
    protected $statusService;

    public function __construct(StatusDeviceService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function getDeviceStatus()
    {
        try {
            $data = $this->statusService->getDeviceStatus();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil status device: ' . $e->getMessage()], 500);
        }
    }

    public function getDashboardStats()
    {
        try {
            $stats = $this->statusService->getDashboardStats();
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik dashboard: ' . $e->getMessage()], 500);
        }
    }
}