<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseDrafted extends Notification
{
    use Queueable;

    public $course;
    public $instructor;
    public $time;
    public $url;

    public function __construct(Course $course, User $instructor)
    {
        $this->course = $course;
        $this->instructor = $instructor;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y');
        $this->url = route('admin.courses.edit', ['id' => $course->id, 'step' => 1]);
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
            'title' => "Course Drafted",
            'message' => "Instructor '{$this->instructor->name}' created new course draft {$this->course->title}!",
            'instructor_id' => $this->instructor->id,
            'time' => $this->time,
            'url' => $this->url
        ];
    }
}
