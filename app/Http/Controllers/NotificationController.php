<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $notifications = auth('web')->user()->notifications;
        return view('frontend.notification.index', compact('notifications'));
    }

    public function fetchMessages() {
        $notifications = auth('web')->user()->notifications()->limit(10)->get();
        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead($id) {
        $user = auth('web')->user();
        $notification = $user->notifications()->find($id);
        $notification->markAsRead();
        return response(['status' => 'success']);
    }

    public function destroy($id) {
        $user = auth('web')->user();
        $notification = $user->notifications()->find($id);
        $notification->delete();
        return back();
    }
}
