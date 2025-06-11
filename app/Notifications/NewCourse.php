<?php

namespace App\Notifications;

use App\Models\Admin;
use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCourse extends Notification
{
    use Queueable;

    public $course;
    public $instructor;
    public $time;
    public $url;

    public function __construct(Course $course, User $instructor, $url)
    {
        $this->course = $course;
        $this->instructor = $instructor;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y');
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['broadcast', 'database'];
        return ['database'];
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'message' => "Instructor {$this->instructor->name} created new course {$this->course->title}!",
    //         'instructor_id' => $this->instructor->id,
    //         'time' => $this->time,
    //         'url' => $this->url
    //     ]);
    // }

    public function toArray($notifiable)
    {
        return [
            'title' => "New Course",
            'message' => "Instructor {$this->instructor->name} created new course {$this->course->title}!",
            'instructor_id' => $this->instructor->id,
            'time' => $this->time,
            'url' => $this->url
        ];
    }
}
