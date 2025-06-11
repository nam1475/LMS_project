<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentEnrolledCourse extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $course;
    public $student;
    public $instructor;
    public $time;
    public $url;

    public function __construct(Course $course, User $student, User $instructor)
    {
        $this->course = $course;
        $this->student = $student;
        $this->instructor = $instructor;
        $this->time = now()->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y');
        $this->url = route('instructor.courses.enrolled-students', ['id' => $course->id, 'search' => $student->email]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['broadcast'];
        return ['database'];
    }

    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'message' => "Student {$this->student->name} has enrolled course {$this->course->title}!",
    //         'instructor_id' => $this->instructor->id,
    //         'time' => $this->time
    //     ]);
    // }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('notificationssss.' . $this->instructor->id);
    // }

    // public function broadcastAs()
    // {
    //     return 'student.enrolled.course';
    // }

    public function toArray($notifiable)
    {
        return [
            'title' => "Student Enrolled Course",
            'message' => "Student {$this->student->name} has enrolled course {$this->course->title}!",
            'instructor_id' => $this->instructor->id,
            'time' => $this->time,
            'url' => $this->url
        ];
    }
}
