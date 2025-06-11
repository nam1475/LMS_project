<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Events\SendChatMessage;
use App\Models\Cart;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\StudentEnrolledCourse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();
        $contacts = Chat::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)
                ->pluck($user->role == 'student' ? 'receiver_id' : 'sender_id')->unique()->toArray();
        // Fetch users based on the role
        if ($user->role == 'instructor') {
            $senders = User::where('role', 'student')->whereIn('id', $contacts)->get();
        } else {
            $senders = User::where('role', 'instructor')->whereIn('id', $contacts)->get();
        }
        
        return view('frontend.chat.index', [
            'user' => $user,
            'senders' => $senders,
        ]);

    }

    // public function markAsNotRead(Request $request)
    // {
    //     $user = auth('web')->user();
    //     $receiverId = $request->input('receiver_id');
    //     $user->messages($receiverId)->update(['is_read' => false]);
    //     return response()->json(['success' => true]);
    // }

    public function fetchMessages(Request $request)
    {
        $user = auth('web')->user();
        $receiverId = $request->input('receiver_id');

        $messages = Chat::where(function ($query) use ($user, $receiverId) {
            $query->where(['sender_id' => $user->id, 'receiver_id' => $receiverId]);
        })->orWhere(function ($query) use ($user, $receiverId) {
            $query->where(['sender_id' => $receiverId, 'receiver_id' => $user->id]);
        })->orderBy('created_at', 'asc')->get();
        
        $isRead = $user->unreadMessages($receiverId)->update(['is_read' => true]);

        // $cartItems = Cart::with('course.instructor')->where('user_id', $user->id)->get();
        // // foreach($cartItems as $item) {
        // //     $instructor = $item->course->instructor;
        // //     $instructor->notify(new StudentEnrolledCourse($item->course, $user, $instructor));
        // // }
        // $instructor = $cartItems[0]->course->instructor;
        // $instructor->notify(new StudentEnrolledCourse($cartItems[0]->course, $user, $instructor));

        return response()->json(['messages' => $messages, 'isRead' => $isRead, 'receiverId' => $receiverId]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string'],
            'receiver_id' => ['required', 'exists:users,id'],
        ]);
        $receiverId = $request->input('receiver_id');
        $message = $request->input('message');
        $user = auth('web')->user();

        $chat = Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $message,
        ]);

        $isRead = $user->unreadMessages($receiverId)->update(['is_read' => true]);

        event(new SendChatMessage($message, $user->id, $receiverId, $user->name, $user->image));
        
        return response()->json(['chat' => $chat, 'success' => true, 'isRead' => $isRead]);
    }
}
