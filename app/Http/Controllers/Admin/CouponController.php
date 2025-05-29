<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponCourseCategory;
use App\Models\CourseCategory;
use App\Models\User;
use App\Service\CouponService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $coupons = Coupon::with('courseCategories', 'instructor')->paginate(25);
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseCategories = CourseCategory::where('status', 1)->get();
        return view('admin.coupon.create', compact('courseCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->couponService->storeForAdmin($request);
        if ($result) {
            notyf()->success('Created Successfully!');
            return redirect()->route('admin.coupons.index');
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
        return view('admin.coupon.edit', compact('coupon', 'courseCategories', 'courseCategoryIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = $this->couponService->update($request, $id);
        if ($result) {
            notyf()->success('Updated Successfully!');
            return redirect()->route('admin.coupons.index');
        }
        notyf()->error('Failed to update!');
        return back();

    }

    function updateApproval(Request $request, Coupon $coupon) {
        $coupon->is_approved = $request->status;
        $coupon->status = 1;
        $coupon->save();

        return response(['status' => 'success', 'message' => 'Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        notyf()->success('Deleted Successfully!');
        return response(['message' => 'Deleted Successfully!'], 200);
    }
}
