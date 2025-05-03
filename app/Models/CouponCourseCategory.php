<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CouponCourseCategory extends Model
{
    use HasFactory;

    protected $table = 'coupon_course_category';

    protected $fillable = [
        'coupon_id',
        'course_category_id',
    ];

    // public function coupon()
    // {
    //     return $this->belongsTo(Coupon::class);
    // }

    // public function courseCategory()
    // {
    //     return $this->belongsTo(CourseCategory::class);
    // }
    
}
