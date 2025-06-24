<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorRequestRejected extends Notification
{
    use Queueable;

    public $message;
    public $document;
    public $time;
    public $url;

    public function __construct($message, $document)
    {
        $this->message = $message;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y');
        // $this->url = route('student.become-instructor');
        $this->url = asset("{$document}");
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => "Instructor Request Rejected",
            'message' => "Your instructor request was rejected ({$this->message})!",
            'time' => $this->time,
            'url' => $this->url
        ];
    }
}
