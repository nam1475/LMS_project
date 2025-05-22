<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SentChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender_id;
    public $receiver_id;
    public $sender_name;
    public $sender_image;
    public $time;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $sender_id, $receiver_id, $sender_name, $sender_image)
    {
        $this->message = $message;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->sender_name = $sender_name;
        $this->sender_image = $sender_image ?? '/default-files/avatar.png'; 
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->receiver_id); 
    }

    public function broadcastAs()
    {
        return 'student-instructor-message';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'sender_name' => $this->sender_name,
            'sender_image' => $this->sender_image,
            'time' => $this->time,
        ];
    }
}
