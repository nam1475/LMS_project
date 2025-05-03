<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponCourseCategory;
use App\Models\CourseCategory;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::paginate(25);
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role', 'student')->get();
        $courseCategories = CourseCategory::where('status', 1)->get();
        return view('admin.coupon.create', compact('students', 'courseCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|in:fixed,percent',
            'value' => [
                'required',
                'integer',
                Rule::when($request->type == 'fixed', ['min:1000']),
                Rule::when($request->type == 'percent', ['min:1', 'max:100']),
            ],
            'minimum_order_amount' => 'required|integer|min:1000',
            'expire_date' => 'required|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'minimum_order_amount' => $request->minimum_order_amount,
            'expire_date' => $request->expire_date,
            'status' => $request->status ?? 0
        ]);
        
        $courseCategoryIds = $request->course_category_id;
        $coupon->courseCategories()->attach($courseCategoryIds);

        notyf()->success('Created Successfully!');

        return redirect()->route('admin.coupons.index');
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

        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|in:fixed,percent',
            'value' => [
                'required',
                'integer',
                Rule::when($request->type == 'fixed', ['min:1000']),
                Rule::when($request->type == 'percent', ['min:1', 'max:100']),
            ],
            'minimum_order_amount' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Cho phép 0 là mặc định, còn nếu không phải 0 thì phải >= 1000
                    if ($value != 0 && $value < 1000) {
                        $fail('Min order amount must be 0 or greater than 1000.');
                    }
                },
            ],
            'course_category_id' => 'required|array',
            'expire_date' => 'nullable|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'minimum_order_amount' => $request->minimum_order_amount,
            'expire_date' => $request->expire_date,
            'status' => $request->status ?? 0
        ]);

        $courseCategoryIds = $request->course_category_id;
        $coupon->courseCategories()->sync($courseCategoryIds);

        notyf()->success('Updated Successfully!');

        return redirect()->route('admin.coupons.index');

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
