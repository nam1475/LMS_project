<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Notifications\CourseRejected;
use App\Notifications\InstructorRequestRejected;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RejectApproval extends Controller
{
    function rejectApprovalModal(Request $request, string $id){
        $routeType = $request->route_type;
        return view('admin.course.course-module.partials.reject-modal', compact('id', 'routeType'))->render();
    }

    function sendRejectApproval(Request $request, string $id){
        if($request->route_type == 'course'){
            $course = Course::withoutGlobalScopes()->find($id);
            $course->withoutRevision();
            $course->update([
                'message_for_rejection' => $request->message,
                'is_approved' => 'rejected',
            ]);
            $course->instructor->notify(new CourseRejected($course, $course->instructor));   
        }
        else if($request->route_type == 'instructor_request'){
            $user = User::find($id);
            $user->update([
                'message_for_rejection' => $request->message,
                'approve_status' => 'rejected',
            ]);
            $user->notify(new InstructorRequestRejected($request->message, $user->document));
        }
        notyf()->success('Send message successfully.');
        
        return redirect()->back();
    }
}
