<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CourseCategory;
use App\Service\CouponService;

class CouponController extends Controller
{
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseCategories = CourseCategory::with('subCategories')->where('status', 1)->get();
        $coupons = Coupon::with('courseCategories')
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('status') && $request->filled('status'), function($query) use ($request) {
                if($request->status == 'all'){
                    return $query;
                }
                $query->where('status', $request->status);
            })
            ->when($request->has('is_approved') && $request->filled('is_approved'), function($query) use ($request) {
                if($request->is_approved == 'all'){
                    return $query;
                }
                $query->where('is_approved', $request->is_approved);
            })
            ->when($request->has('course_categories') && $request->filled('course_categories'), function($query) use ($request) {
                if($request->course_categories == 'all'){
                    return $query;
                }
                $query->whereHas('courseCategories', function ($q) use ($request) {
                    $q->whereIn('course_categories.id', $request->course_categories);
                });
            })
            ->when($request->has('type') && $request->filled('type'), function($query) use ($request) {
                if($request->type == 'all'){
                    return $query;
                }
                $query->where('type', $request->type);
            })
            ->where('instructor_id', auth('web')->user()->id)->paginate(25);
        // dd($coupons);
        return view('frontend.instructor-dashboard.coupon.index', compact('coupons', 'courseCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseCategories = CourseCategory::where('status', 1)->get();
        return view('frontend.instructor-dashboard.coupon.create', compact('courseCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->couponService->storeForInstructor($request);
        if ($result) {
            notyf()->success('Created Successfully!');
            return redirect()->route('instructor.coupons.index');
        }
        notyf()->error('Failed to create!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $courseCategories = CourseCategory::where('status', 1)->get();
        $courseCategoryIds = $coupon->courseCategories->pluck('id')->toArray();
        return view('frontend.instructor-dashboard.coupon.edit', compact('coupon', 'courseCategories', 'courseCategoryIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->couponService->update($request, $id);
        if ($result) {
            notyf()->success('Updated Successfully!');
            return redirect()->route('instructor.coupons.index');
        }
        notyf()->error('Failed to update!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $result = $coupon->delete();
        if($result){
            notyf()->success('Deleted Successfully!');
            return back();
        }
        notyf()->error('Failed to delete!');
        return back();
    }



    
}
