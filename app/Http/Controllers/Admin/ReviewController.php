<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::withoutGlobalScopes()
            ->whereHas('reviews', function($query){
                $query->where('status', 1);
            })
            ->where(['is_published' => 1, 'is_approved' => 'approved'])
            ->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.review.course-reviews', compact('courses'));
    }

    public function show($courseId){
        $reviews = Review::with(['user'])->where('course_id', $courseId)->latest()->paginate(20);
        return view('admin.review.index', compact('reviews'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $review->status = $request->status ? 1 : 0;
        $review->save();

        notyf()->success('Updated Successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        }catch(Exception $e) {
            logger("Course Ratting Error >> ".$e);
            return response(['message' => 'Something went wrong!'], 500);
        }

    
    }
}
