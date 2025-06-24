<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index() {
        $notifications = auth('admin')->user()->notifications()->paginate(25);
        return view('admin.notification.index', compact('notifications'));
    }

    public function fetchMessages() {
        $notifications = auth('admin')->user()->notifications()->limit(3)->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead($id) {
        $admin = auth('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->first();
        $notification->markAsRead();
        return response(['status' => 'success']);
    }

    public function destroy($id) {
        $user = auth('web')->user();
        $notification = $user->notifications()->where('id', $id)->first();
        $notification->delete();
        return back();
    }

    
}
