<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseReviewController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::withoutGlobalScopes()
            ->whereHas('reviews', function($query){
                $query->where('status', 1);
            })
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->where(['is_published' => 1, 'is_approved' => 'approved', 'instructor_id' => auth('web')->user()->id])
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('frontend.instructor-dashboard.course.reviews.index', compact('courses'));
    }

    public function show(Request $request, $id)
    {
        $course = Course::withoutGlobalScopes()->find($id);
        $reviews = $course->reviews()->with('user')
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->whereHas('user', function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->has('rating') && $request->filled('rating'), function($query) use ($request) {
                if($request->rating == 'all'){
                    return $query;
                }
                $query->whereIn('rating', $request->rating);
            })
            ->where('status', 1)->orderBy('created_at', 'desc')->paginate(25);
        return view('frontend.instructor-dashboard.course.reviews.show', compact('course', 'reviews'));
    }
}
