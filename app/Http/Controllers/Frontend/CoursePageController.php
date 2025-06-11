<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoursePageController extends Controller
{
    function index(Request $request): View
    {
        // filled(): Checks if the given key has a value in the request
        $courses = Course::withoutGlobalScopes()->where(['is_approved' => 'approved'])->published()
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhere(function($query) use ($request) {
                    $query->whereHas('instructor', function($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    });
                });
            })
            ->when($request->has('category') && $request->filled('category'), function($query) use ($request) {
                if(is_array($request->category)){
                    $query->whereIn('category_id', $request->category);
                }else {
                    $query->where('category_id', $request->category);
                }
            })
            ->when($request->filled('main_category'), function($query) use ($request) {
                $query->whereHas('category', function($query) use ($request) {
                    $query->whereHas('parentCategory', function($query) use ($request){
                        $query->where('slug', $request->main_category);
                    });
                });
            })
            ->when($request->has('level') && $request->filled('level'), function($query) use ($request) {
                $query->whereIn('course_level_id', $request->level);
            })
            ->when($request->has('language') && $request->filled('language'), function($query) use ($request) {
                $query->whereIn('course_language_id', $request->language);
            })
            ->when($request->has('from') && $request->has('to') && $request->filled('from') && $request->filled('to'), function($query) use ($request) {
                $query->whereBetween('price', [$request->from, $request->to]);
            })
            ->orderBy('updated_at', $request->filled('order') ? $request->order : 'desc')
            ->paginate(12);

        $categories = CourseCategory::where('status', 1)->whereNull('parent_id')->get();
        $levels = CourseLevel::all();
        $languages = CourseLanguage::all();

        return view('frontend.pages.course-page', compact('courses', 'categories', 'levels', 'languages'));
    }

    function show(string $slug): View
    {
        $course = Course::withoutGlobalScopesWithRelations()->with(['reviews'])->where('slug', $slug)
            ->where(['is_approved' => 'approved', 'is_published' => true])
            ->firstOrFail();
        $reviews = Review::where(['course_id' => $course->id, 'status' => true])->orderBy('created_at', 'desc')->get();
        $ratingPercentages = $this->getRatingPercentages();
        // dd($ratingPercentages);
        $user = auth('web')->user();
        $courseEnrolled = $user ? Enrollment::where(['user_id' => $user->id, 'course_id' => $course->id])->first() : null;
        $isCourseAddedToCart = $user ? Cart::where(['user_id' => $user->id, 'course_id' => $course->id])->exists() : null;
        $isReviewed = $user ? Review::where(['user_id' => $user->id, 'course_id' => $course->id])->exists() : null;
        
        return view('frontend.pages.course-details-page', compact(
            'course', 'reviews', 'user', 'courseEnrolled', 'isCourseAddedToCart', 'isReviewed', 'ratingPercentages'
        ));
    }

    public function getRatingPercentages()
    {
        // Lấy số lượng review theo từng rating (1-5)
        $counts = Review::groupBy('rating')
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->pluck('count', 'rating');

        // Đảm bảo có đủ 5 mức rating, nếu thiếu thì thêm giá trị 0
        $ratings = collect([1, 2, 3, 4, 5]);
        $counts = $ratings->mapWithKeys(function ($rating) use ($counts) {
            return [$rating => $counts->get($rating, 0)];
        });

        // Tính tổng số review
        $total = $counts->sum();

        // Nếu không có review thì trả về 0%
        if ($total === 0) {
            return $ratings->mapWithKeys(fn($r) => [$r => 0]);
        }

        // Tính phần trăm từng rating
        $percentages = $counts->map(function ($count) use ($total) {
            return round($count * 100 / $total, 2);
        });

        // Điều chỉnh để tổng phần trăm bằng 100%
        $diff = 100 - $percentages->sum();
        $percentages[5] += $diff; // cộng phần dư vào mức 5 sao

        return $percentages;
    }


    function getReviews(Request $request, $courseId)
    {
        $reviews = Review::with('user')->where([
            'course_id' => $courseId, 'status' => true]);
        if($request->rating != 'all'){
            $reviews->where('rating', $request->rating);
        }
        return response()->json(['status' => 'success', 'reviews' => $reviews->get()]);
    }

    function storeReview(Request $request) : RedirectResponse
    {
       $request->validate([
        'rating' => ['required', 'numeric'],
        'review' => ['required', 'string', 'max:1000'],
        'course' => ['required', 'integer']
       ]);

       $checkPurchase = Enrollment::where('user_id', user()->id)->where('course_id', $request->course)->exists();
       $alreadyReviewed = Review::where('user_id', user()->id)->where('course_id', $request->course)->where('status', 1)->exists();

       if(!$checkPurchase) {
        notyf()->error('Please Purchase Course First!');
        return redirect()->back();
       }

       if($alreadyReviewed) {
        notyf()->error('You Already Reviewed This Course!');
        return redirect()->back();
       }

       $review = new Review();
       $review->user_id = user()->id;
       $review->rating = $request->rating;       
       $review->course_id = $request->course;
       $review->review = $request->review;
       $review->save();

       notyf()->success('Review Submitted Successfully!');
       return redirect()->back();
    }
}
