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
    public function index()
    {
        $coupons = Coupon::where('instructor_id', auth('web')->user()->id)->with('courseCategories')->paginate(25);
        // dd($coupons);
        return view('frontend.instructor-dashboard.coupon.index', compact('coupons'));
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
        $result = $this->couponService->store($request);
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
