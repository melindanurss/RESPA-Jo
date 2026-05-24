<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::query()
            ->when($request->filled('device_type'), function ($q) use ($request) {
                $q->where('device_type', $request->device_type);
            })
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($devices);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'device_key' => 'required|string|unique:devices,device_key',
                'device_type' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
            }

            $device = Device::create(array_merge($validator->validated(), ['is_active' => true]));

            return response()->json(['status' => 'success', 'message' => 'Device created successfully', 'device' => $device], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Device not found'], 404);
        }
        return response()->json(['status' => 'success', 'device' => $device], 200);
    }

    public function update(Request $request, $id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Device not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'device_key' => 'sometimes|required|unique:devices,device_key,' . $id,
            'device_type' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $device->update($validator->validated());
        return response()->json(['status' => 'success', 'message' => 'Device updated successfully', 'device' => $device], 200);
    }

    public function destroy($id)
    {
        try {
            $device = Device::find($id);
            if (!$device) {
                return response()->json(['status' => 'error', 'message' => 'Device not found'], 404);
            }
            $device->delete();
            return response()->json(['status' => 'success', 'message' => 'Device deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete device', 'error' => $e->getMessage()], 500);
        }
    }
}