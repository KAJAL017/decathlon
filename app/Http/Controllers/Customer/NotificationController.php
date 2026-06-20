<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $notifications = $customer->notifications_panel()->latest()->paginate(20);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'notifications' => $notifications]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.notifications' : 'customer.desktop.notifications', compact('notifications'));
    }

    public function markRead(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $notification = $customer->notifications_panel()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        Cache::forget("customer_{$customer->id}_unread_notifications");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $customer->notifications_panel()
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        Cache::forget("customer_{$customer->id}_unread_notifications");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function unreadCount(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $count = Cache::remember("customer_{$customer->id}_unread_notifications", 60, function () use ($customer) {
            return $customer->notifications_panel()->unread()->count();
        });

        return response()->json(['success' => true, 'count' => $count]);
    }
}
