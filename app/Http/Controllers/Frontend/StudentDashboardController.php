<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewInstructorRequest;
use App\Traits\FileUpload;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class StudentDashboardController extends Controller
{
    use FileUpload;

    function index() : View {
        $userCourses = auth('web')->user()->enrollments()->count();
        $reviewCount = Review::where('user_id', user()->id)->count();
        $orderCount = Order::where('buyer_id', user()->id)->count();

        $orders = Order::where('buyer_id', user()->id)->orderBy('created_at', 'desc')->paginate(25);
        
        return view('frontend.student-dashboard.index', compact('userCourses', 'reviewCount', 'orderCount', 'orders'));
    }


    function becomeInstructor() : View {
       if(auth('web')->user()->role == 'instructor') abort(403);

       return view('frontend.student-dashboard.become-instructor.index'); 
    }
    
    function becomeInstructorUpdate(Request $request, User $user) : RedirectResponse {
        $request->validate(['document' => ['required', 'mimes:pdf,doc,docx,jpg,png', 'max:12000']]);

        $filePath = $this->uploadFile($request->file('document'));
        $this->deleteFile($user->document);
        
        $user->update([
            'approve_status' => 'pending',
            'document' => $filePath
        ]);

        $admin = Admin::find(1);
        $admin->notify(new NewInstructorRequest($user));

        return redirect()->route('student.dashboard');
    }

    function review() : View
    {
        $reviews = Review::where('user_id', user()->id)->paginate(10);
        return view('frontend.student-dashboard.review.index', compact('reviews'));
    }

    function reviewDestroy(string $id) {
       try {
           $review = Review::where('id', $id)->where('user_id', user()->id)->firstOrFail();
           $review->delete();
           notyf()->success('Deleted Successfully!');
           return response(['message' => 'Deleted Successfully!'], 200);
       } catch (Exception $e) {
           logger("Review Error >> " . $e);
           return response(['message' => 'Something went wrong!'], 500);
       } 
    }
}
