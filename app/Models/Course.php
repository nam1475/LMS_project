<?php

namespace App\Models;

use App\Traits\Draft;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Oddvalue\LaravelDrafts\Concerns\HasDrafts;

class Course extends Model
{
    use HasFactory, HasDrafts, Draft;

    protected $fillable = [
        'title',
        'slug',
        'duration',
        'demo_video_storage',
        'demo_video_source',
        'seo_description',
        'description',
        'price',
        'discount_price',
        'category_id',
        'course_level_id',
        'course_language_id',
        'thumbnail',
        'is_approved',
        'instructor_id',
        'capacity',
        'qna',
        'certificate',
        'is_published',
        'is_current',
        'message_for_reviewer',
        'message_for_rejection'
    ];

    protected array $draftableRelations = ['chapters']; // Nếu có quan hệ cần quản lý trong draft

    // protected static function booted()
    // {
    //     static::retrieved(function ($course) {
    //         if($course->is_approved == 'pending' || $course->is_approved == 'rejected') {
    //             $course->update(['is_published' => false]);
    //         }
    //     }); 
    // }

    // public function scopeCurrentWithoutRevision($query, $courseId)
    // {
    //     return $query->where('id', $courseId)->current()->first()->withoutRevision();
    // }

    public function scopeWithoutGlobalScopesWithRelations($query){
        return $query->withoutGlobalScopes()->with([
            'chapters' => fn($q) => $q->withoutGlobalScopes(),
            'chapters.lessons' => fn($q) => $q->withoutGlobalScopes(),
        ]);
    }
    
    public function publishWithRelations()
    {
        // publish(): Đổi DL giữa bản nháp <-> bản chính ngoại trừ id
        return DB::transaction(function () {
            $publishedCourse = $this->publish();
            $publishedCourse->save(); 
            
            
            $allCourse = Course::withDrafts()->where(['uuid' => $this->uuid])
                ->get();
            $allCourse->each(function ($course) {
                Log::info('Course: ' . $course); 
            });
            // Sau khi publish thì bản nháp sẽ ko còn là bản current
            if(!$this->isCurrent()){
                // Lấy bản course chính hiện tại đã publish
                $mainCourse = Course::withDrafts()->where(['uuid' => $this->uuid, 'is_published' => true])
                    ->first();
                Log::info('Main course: ' . $mainCourse);
            }
            if ($publishedCourse) {
                foreach ($publishedCourse->chapters as $chapter) {
                    $publishedChapter = $chapter->publish();
                    $publishedChapter->withoutRevision();
                    // Log::info('Before save: ' . $publishedChapter);
                    $publishedChapter->course_id = $this->id;
                    $publishedChapter->save(); 
                    // Log::info('After save chapter: ' . $publishedChapter);
                    // Log::info('Draft chapter: ' . $chapter);
                    
                    if(!$this->isCurrent()){
                        // Update lại course_id của chapter bản vừa publish 
                        $mainChapter = CourseChapter::where(['uuid' => $chapter->uuid, 'is_published' => true])
                            ->first()->withoutRevision();
                        $mainChapter->update(['course_id' => $mainCourse->id]);
                        Log::info('Chapter: ' . $mainChapter);
                    }
                    
                    foreach ($publishedChapter->lessons as $lesson) {
                        $publishedLesson = $lesson->publish();
                        $publishedLesson->withoutRevision();
                        $publishedLesson->chapter_id = $publishedChapter->id;
                        $publishedLesson->course_id = $this->id;
                        $publishedLesson->save();  
                        // Log::info('After save lesson: ' . $publishedLesson);
                        
                        if(!$this->isCurrent()){
                            // Update lại course_id và chapter_id của lesson bản vừa publish
                            $mainLesson = CourseChapterLession::where(['uuid' => $lesson->uuid, 'is_published' => true])
                                ->first()->withoutRevision();
                            $mainLesson->update(['chapter_id' => $mainChapter->id]);
                            $mainLesson->update(['course_id' => $mainCourse->id]);
                            Log::info('Lesson: ' . $mainLesson);
                        }
                    }
                }
                
                
                // $mainChapters = CourseChapter::with(['lessons' => fn($q) => $q->current()])
                //         ->orderBy('id', 'desc')->where('course_id', $this->id)->current()->get();
                // $mainChapters = CourseChapter::whereIn('id', [564, 566])->orderBy('id', 'desc')
                //     ->current()->get();
                // $mainLessons = CourseChapterLession::whereIn('id', [2494, 2496])
                //         ->orderBy('id', 'desc')->current()->get();
                // Log::info($mainChapters);   
                // Log::info('Main lessons after publish: ' . $mainLessons);   
                
                // foreach($mainChapters as $chapter) {
                //     $chapter->withoutRevision();
                //     $chapter->update(['course_id' => $mainCourse->id]);
                //     // foreach($item->lessons as $lesson) {
                //     //     $lesson->withoutRevision();
                //     //     $lesson->update(['course_id' => $mainCourse->id, 'chapter_id' => $item->id]);
                //     //     Log::info($lesson);
                //     // }
                //     foreach($mainLessons as $lesson) {
                //         $lesson->withoutRevision();
                //         $lesson->update(['course_id' => $mainCourse->id, 'chapter_id' => $chapter->id]);
                //     }
                // }
                // Log::info($mainChapters);   
                // Log::info($mainLessons);   


                return true;
            }

            return false;
        });
    }


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
        // return $this->hasMany(CourseChapter::class, 'course_id', 'id')->orderBy('order');
        return $this->hasMany(CourseChapter::class, 'course_id', 'id');
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
