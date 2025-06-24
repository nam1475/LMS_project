<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInstructorRequest extends Notification
{
    use Queueable;

    public $student;
    public $time;
    public $url;

    public function __construct(User $student)
    {
        $this->student = $student;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y');
        $this->url = route('admin.instructor-requests.index', ['search' => $student->email]);
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
            'title' => "New Instructor Request",
            'message' => "User {$this->student->name} has requested to become an instructor!",
            'time' => $this->time,
            'url' => $this->url
        ];
    }
}
