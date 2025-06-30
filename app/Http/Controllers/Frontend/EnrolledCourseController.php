<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapterLession;
use App\Models\Enrollment;
use App\Models\WatchHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnrolledCourseController extends Controller
{
    function index() : View
    {
        $enrollments = Enrollment::with('course')->where('user_id', user()->id)->orderBy('created_at', 'desc')->get();
        return view('frontend.student-dashboard.enrolled-course.index', compact('enrollments'));     
    }

    function enrollFreeCourse(Request $request, $courseId) : Response
    {
        $isEnrolled = Enrollment::where(['user_id' => $request->user_id, 'course_id' => $courseId, 'have_access' => 1])->exists();
        if($request->is_free && !$isEnrolled) {
            Enrollment::create([
                'user_id' => $request->user_id,
                'course_id' => $courseId,
                'instructor_id' => $request->instructor_id,
            ]);
            return response(['status' => 'success', 'message' => 'Enrolled free course Successfully!']);
        }
        return response(['status' => 'success']);
    }

    function playerIndex(string $slug) : View
    {
        $user = auth('web')->user();
        $course = Course::withoutGlobalScopesWithRelations()->where('slug', $slug)->firstOrFail();
        $isEnrolled = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->where('have_access', 1)->exists();
        if(!$isEnrolled && !$user->role == 'instructor') {
            return abort(404);
        }
        // if($user->role == 'instructor'){
        //     Enrollment::create([
        //         'user_id' => $user->id,
        //     ]);
        // }
        $lessonCount = CourseChapterLession::withoutGlobalScopes()->where('course_id', $course->id)->count();
        $lastWatchHistory = WatchHistory::where(['user_id' => $user->id, 'course_id' => $course->id])->orderBy('updated_at', 'desc')->first();
        $watchedLessonIds = WatchHistory::where(['user_id' => $user->id, 'course_id' => $course->id, 'is_completed' => 1])->pluck('lesson_id')->toArray();
        // $totalComments = $course->comments()->count();

        return view('frontend.student-dashboard.enrolled-course.player-index', 
                compact('course', 'lastWatchHistory', 'watchedLessonIds', 'lessonCount', 'user'));
    }

    function getLessonContent(Request $request) 
    {
        $lesson = CourseChapterLession::withoutGlobalScopes()->where([
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'id' => $request->lesson_id
        ])->first();

        return response()->json($lesson);
    }

    function updateWatchHistory(Request $request) {
       WatchHistory::updateOrCreate(
        [
            'user_id' => user()->id,
            'lesson_id' => $request->lesson_id
        ],
        [
        'course_id' => $request->course_id,
        'chapter_id' => $request->chapter_id,
        'updated_at' => now()
       ]);
    }

    function updateLessonCompletion(Request $request) : Response
    {
        $watchedLesson = WatchHistory::where([
            'user_id' => user()->id,
            'lesson_id' => $request->lesson_id
        ])->first();

        WatchHistory::updateOrCreate(
            [
                'user_id' => user()->id,
                'lesson_id' => $request->lesson_id
    
            ],
            [
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'is_completed' => $watchedLesson->is_completed == 1 ? 0 : 1,
           ]);

        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }

    function fileDownload(string $id)
    {
        $lesson = CourseChapterLession::withoutGlobalScopes()->findOrFail($id);
        return response()->download(public_path($lesson->file_path));     
    }
}
