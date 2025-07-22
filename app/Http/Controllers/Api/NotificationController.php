<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get the user's notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->get();
        
        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $notificationId): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return response()->json(['message' => 'Notification marked as read.']);
    }
}
