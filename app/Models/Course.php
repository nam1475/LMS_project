<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'discount_price',
        'category_id',
        'course_level_id',
        'course_language_id',
        'thumbnail',
        'is_approved',
        'status',
        'instructor_id',
    ];

    // Automatic retrieve coupon code
    public function getCouponCodeAutoOption()
    {
        return Coupon::whereHas('courseCategories', function($query) {
                $query->where('course_category_id', $this->category_id);
            })
            ->where('option', 'auto')
            ->where('status', 1)
            ->where('expire_date', '>=', Carbon::now())
            ->pluck('code')
            ->first();
    }

    // Automatic set session coupon code
    // protected static function booted()
    // {
    //     static::retrieved(function ($course) {
    //         $courseCategoryId = $course->category_id;
    //         $coupon = Coupon::whereHas('courseCategories', function($query) use ($courseCategoryId) {
    //             // dd($course->category_id);
    //             $query->where('course_category_id', $courseCategoryId);
    //         })
    //         ->where('option', 'auto')
    //         ->where('status', 1)
    //         ->where('expire_date', '>=', Carbon::now())
    //         ->pluck('code')
    //         ->first();
    //         session(['coupon_code' => $coupon]);
    //     });
    // }

    function instructor() : HasOne{
        return $this->hasOne(User::class, 'id', 'instructor_id');
    }

    function category() : HasOne
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category_id');
    }

    function level() : HasOne{
        return $this->hasOne(CourseLevel::class, 'id', 'course_level_id');
    }
    function language() : HasOne{
        return $this->hasOne(CourseLanguage::class, 'id', 'course_language_id');
    }

    function chapters() : HasMany
    {
        return $this->hasMany(CourseChapter::class, 'course_id', 'id')->orderBy('order');
    }

    function lessons() : HasMany
    {
        return $this->hasMany(CourseChapterLession::class, 'course_id', 'id');
    }

    function reviews() : HasMany
    {
        return $this->hasMany(Review::class, 'course_id', 'id');
    }

    function enrollments() : HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id', 'id');
    }

}
