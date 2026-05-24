<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Notification::query();
            if ($request->filled('type')) $query->where('type', $request->type);
            if ($request->filled('is_read')) $query->where('is_read', filter_var($request->is_read, FILTER_VALIDATE_BOOLEAN));
            
            $perPage = $request->get('per_page', 20);
            $notifications = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            $formattedNotifications = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id, 'type' => $notification->type, 'title' => $notification->title,
                    'message' => $notification->message, 'data' => $notification->data, 'severity' => $notification->severity,
                    'is_read' => $notification->is_read, 'created_at' => $notification->created_at->format('d/m/Y H:i:s'),
                    'time_ago' => $notification->created_at->diffForHumans(), 'icon' => $notification->icon,
                    'badge_class' => $notification->badge_class, 'action_url' => $notification->data['action_url'] ?? null
                ];
            });
            
            return response()->json([
                'success' => true, 'data' => $formattedNotifications,
                'unread_count' => Notification::where('is_read', false)->count(),
                'pagination' => ['current_page' => $notifications->currentPage(), 'total_pages' => $notifications->lastPage(),
                    'total_items' => $notifications->total(), 'per_page' => $notifications->perPage()]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil notifikasi: ' . $e->getMessage()], 500);
        }
    }
    
    public function getUnreadCount()
    {
        try {
            return response()->json(['success' => true, 'unread_count' => Notification::where('is_read', false)->count()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'unread_count' => 0]);
        }
    }
    
    public function markAsRead($id)
    {
        try {
            $notification = Notification::find($id);
            if (!$notification) return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
            $notification->update(['is_read' => true, 'read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Notifikasi telah ditandai sebagai dibaca']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menandai notifikasi'], 500);
        }
    }
    
    public function markAllAsRead()
    {
        try {
            Notification::where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah ditandai sebagai dibaca']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menandai semua notifikasi'], 500);
        }
    }
}