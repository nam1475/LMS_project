<?php

namespace App\Service;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CouponService
{
    public function storeForInstructor($request){
        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|in:fixed,percent',
            'instructor_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'instructor');
                }),
            ],
            'value' => [
                'required',
                'integer',
                Rule::when($request->type == 'fixed', ['min:1000']),
                Rule::when($request->type == 'percent', ['min:1', 'max:100']),
            ],
            'course_category_id' => 'required|array',
            'minimum_order_amount' => 'required|integer|min:0',
            'expire_date' => 'required|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        try{

            DB::beginTransaction();
    
            $coupon = Coupon::create([
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'type' => $request->type,
                'value' => $request->value,
                'minimum_order_amount' => $request->minimum_order_amount,
                'instructor_id' => auth('web')->user()->id,
                'expire_date' => $request->expire_date,
                'status' => $request->status ?? 0,
            ]);
            
            $courseCategoryIds = $request->course_category_id;
            $coupon->courseCategories()->attach($courseCategoryIds);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to create coupon: ' . $e->getMessage());
        }
    }

    public function storeForAdmin($request){
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|in:fixed,percent',
            'value' => [
                'required',
                'integer',
                Rule::when($request->type == 'fixed', ['min:1000']),
                Rule::when($request->type == 'percent', ['min:1', 'max:100']),
            ],
            'course_category_id' => 'required|array',
            'minimum_order_amount' => 'required|integer|min:0',
            'expire_date' => 'required|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        try{

            DB::beginTransaction();
    
            $coupon = Coupon::create([
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'type' => $request->type,
                'value' => $request->value,
                'minimum_order_amount' => $request->minimum_order_amount,
                'expire_date' => $request->expire_date,
                'status' => $request->status ?? 0,
            ]);
            
            $courseCategoryIds = $request->course_category_id;
            $coupon->courseCategories()->attach($courseCategoryIds);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to create coupon: ' . $e->getMessage());
        }
    }

    public function update($request, $id){
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
            'expire_date' => 'required|date|after_or_equal:today',
            'status' => 'boolean'
        ]);

        try{
            DB::beginTransaction();
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
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception('Failed to update coupon: ' . $e->getMessage());
        }
    }

}