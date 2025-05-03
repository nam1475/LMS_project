<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_order_amount',
        'start_date',
        'expire_date',
        'student_id',
        'instructor_id',
        'status',
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'expire_date' => 'datetime',
    //     ];
    // }

    protected static function booted()
    {
        static::retrieved(function ($coupon) {
            if ($coupon->expire_date && Carbon::parse($coupon->expire_date)->lt(now()) && $coupon->status) {
                $coupon->update(['status' => false]);
            }
            // else{
            //     $coupon->update(['status' => true]);
            // }
        }); 
    }

    // Tự động in hoa mã coupon
    // public function code(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => strtoupper($value),
    //     );
    // }
    
    public function findCouponByCode($code) {
        $coupon = Coupon::where(['code' => $code, 'status' => 1])->first();
        return $coupon ?? '';
    }

    public function checkCouponType($couponCode, $total) {
        if($couponCode) {
            $coupon = Coupon::where([
                'code' => $couponCode, 
                'status' => 1, 
            ])
            ->where('expire_date', '>=', Carbon::now())
            ->first();
            if($coupon) {
                if($coupon->type == 'fixed') {
                    if($coupon->value > $total) {
                        $total = 0;
                    }
                    else{
                        $total -= $coupon->value;
                    }
                }
                else if($coupon->type == 'percent') {
                    $total -= ($total * $coupon->value) / 100;
                }
            }
        }
        return $total;
    }

    // public function students()
    // {
    //     return $this->belongsToMany(User::class, 'course_students', 'student_id', 'student_id');
    // }

    // public function instructor()
    // {
    //     return $this->belongsTo(User::class, 'instructor_id', 'instructor_id');
    // }

    public function courseCategories()
    {
        return $this->belongsToMany(CourseCategory::class, 'coupon_course_category', 'coupon_id', 'course_category_id');
    }
}
