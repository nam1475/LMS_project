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

class SendComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $sender;
    public $time;
    public $totalComments;
    public $isReplied;
    public $lessonId;
    public $commentId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $comment, $sender, $totalComments, 
        $isReplied, $lessonId, $commentId)
    {
        $this->comment = $comment;
        $this->sender = $sender;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y'); //Format date time
        $this->totalComments = $totalComments;
        $this->isReplied = $isReplied;
        $this->lessonId = $lessonId;
        $this->commentId = $commentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('course.comment'); 
    }

    public function broadcastAs()
    {
        return 'course.comment';
    }

    public function broadcastWith()
    {
        return [
            'comment' => $this->comment,
            'sender' => $this->sender,
            'time' => $this->time,
            'totalComments' => $this->totalComments,
            'isReplied' => $this->isReplied,
            'lessonId' => $this->lessonId,
            'commentId' => $this->commentId
        ];
    }
}
