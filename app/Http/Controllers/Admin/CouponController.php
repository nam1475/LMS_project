<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;

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
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|in:fixed,percent',
            'value' => 'required|integer|min:1',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'boolean'
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'expire_date' => $request->expire_date,
            'status' => $request->status ?? 0
        ]);
        
        notyf()->success('Created Successfully!');

        return redirect()->route('admin.coupons.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
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
            'value' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'expire_date' => $request->expire_date,
            'status' => $request->status ?? 0
        ]);
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
