<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CourseBasicInfoCreateRequest;
use App\Models\Admin;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseChapter;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Notifications\CourseDrafted;
use App\Notifications\CourseUpdated;
use App\Notifications\NewCourse;
use App\Traits\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use FileUpload;

    function index(Request $request): View
    {
        $courseCategories = CourseCategory::with('subCategories')->where('status', 1)->get();
        $courses = Course::withoutGlobalScopes()->where([
            'instructor_id' => Auth::guard('web')->user()->id, 
            ])
            ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('is_published') && $request->filled('is_published'), function($query) use ($request) {
                if($request->is_published == 'all'){
                    return $query;
                }
                $query->where('is_published', $request->is_published);
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
                $query->whereHas('category', function ($q) use ($request) {
                    $q->whereIn('id', $request->course_categories);
                });
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where(['is_published' => true]);
                })
                // ->orWhere(function ($q) {
                //     $q->where(['is_published' => true, 'is_current' => false]);
                // });
                ->orWhere(function ($q) {
                    $q->where(['is_published' => false, 'is_current' => true]);
                });
            })
            ->orderBy('updated_at', 'DESC')->paginate(25);
        return view('frontend.instructor-dashboard.course.index', compact('courses', 'courseCategories'));
    }

    function showCommits($id): View
    {
        $currentCourse = Course::withoutGlobalScopes()->find($id);
        $courses = Course::withoutGlobalScopes()->where('uuid', $currentCourse->uuid)
            ->orderBy('created_at', 'desc')->get();
        // dd($courses);
        return view('frontend.instructor-dashboard.course.commits', [
            'title' => 'Commits',
            'courses' => $courses,
            'isCommits' => true
        ]);
    }

    function enrolledStudents(Request $request, $id): View
    {
        $course = Course::withoutGlobalScopes()->find($id);
        $enrollments = $course->enrollments()->with('student')
        ->when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->whereHas('student', function($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
                });
        })
        ->get();

        return view('frontend.instructor-dashboard.course.enrolled-students', [
            'title' => 'Enrolled Students',
            'course' => $course,
            'enrollments' => $enrollments
        ]);
    }

    function create(): View
    {
        $categories = CourseCategory::where('status', 1)->get();
        $levels = CourseLevel::all();
        $languages = CourseLanguage::all();
        return view('frontend.instructor-dashboard.course.create', [
            'title' => 'Create Course',
            'categories' => $categories,
            'levels' => $levels,
            'languages' => $languages
        ]);
    }

    function createContent($courseId): View
    {
        $course = Course::withoutGlobalScopes()->find($courseId);
        $chapters = CourseChapter::with(['lessons' => fn($q) => $q->current()])
                        ->where('course_id', $courseId)
                        ->orderBy('order')
                        ->current()
                        ->get();
        return view('frontend.instructor-dashboard.course.course-content', [
            'course' => $course,
            'chapters' => $chapters,
            'isCreateDraft' => true
        ]);
    }

    function storeBasicInfo(CourseBasicInfoCreateRequest $request)
    {
        try{
            DB::beginTransaction();
            $thumbnailPath = $request->hasFile('thumbnail') ? $this->uploadFile($request->file('thumbnail')) : null;
            $course = Course::create([
                'message_for_commit' => $request->message_for_commit,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'seo_description' => $request->seo_description,
                'thumbnail' => $thumbnailPath,
                'demo_video_storage' => $request->demo_video_storage,
                'demo_video_source' => $request->demo_video_source,
                'price' => $request->price,
                'discount' => $request->discount,
                'description' => $request->description,
                'instructor_id' => Auth::guard('web')->user()->id,
                // 'capacity' => $request->capacity,
                // 'qna' => $request->qna ? 1 : 0,
                'certificate' => $request->certificate ? 1 : 0,
                'category_id' => $request->category,
                'course_level_id' => $request->level,
                'course_language_id' => $request->language,
                'is_published' => false,
            ]);
    
            // save course id on session
            Session::put('course_create_id', $course->id);
            
            $admin = Admin::find(1);
            $admin->notify(new NewCourse($course, $course->instructor
                , route('admin.courses.edit', ['id' => $course->id, 'step' => 1])
            ));
            
            DB::commit();
            return response([
                'status' => 'success',
                'message' => 'Created successfully.',
                // 'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                'redirect' => route('instructor.course-content.create', [
                    'id' => $course->id, 
                    'step' => $request->next_step,
                ])
            ]);

        }catch(\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // function compareChaptersWithNestedLessons2($draftCourse, $mainCourse)
    // {
    //     $chapterDiffs = [];

    //     foreach ($draftCourse->chapters as $draftChapter) {
    //         // Tìm chương bản chính tương ứng theo uuid
    //         $originalChapter = $mainCourse->chapters->firstWhere('uuid', $draftChapter->uuid);

    //         // So sánh chapter
    //         $chapterDiff = diffModels($draftChapter, $originalChapter);

    //         // So sánh lessons
    //         $lessonDiffs = [];
    //         foreach ($draftChapter->lessons as $draftLesson) {
    //             $originalLesson = $originalChapter?->lessons->firstWhere('uuid', $draftLesson->uuid);
    //             // $lessonDiffs[] = [
    //             //     'uuid' => $draftLesson->uuid,
    //             //     'diff' => diffModels($draftLesson, $originalLesson),
    //             // ];
    //             $lessonDiffs[$draftLesson->id] = diffModels($draftLesson, $originalLesson);

    //         }

    //         foreach ($mainCourse->chapters as $originalChapter) {
    //             $draftChapter = $draftCourse->chapters->firstWhere('uuid', $originalChapter->uuid);
    //             if (!$draftChapter) {
    //                 $chapterDiffs['new_' . $draftChapter->id] = $originalChapter;
    //             }

    //             foreach ($originalChapter->lessons as $originalLesson) {
    //                 $draftLesson = $draftChapter?->lessons->firstWhere('uuid', $originalLesson->uuid);
    //                 if (!$draftLesson) {
    //                     $chapterDiffs[$draftChapter->id]['new_' . $draftLesson->id] = $originalLesson;
    //                 }
    //             }
    //         }

    //         // Nhét lessonDiffs vào trong chapterDiff
    //         $chapterDiff['lessons'] = $lessonDiffs;

    //         // Thêm vào kết quả
    //         $chapterDiffs[$draftChapter->id] = $chapterDiff;

    //     }
        
    //     return $chapterDiffs;
    // }

    // function compareChaptersWithNestedLessons($draftCourse, $mainCourse)
    // {
    //     $chapterDiffs = [];

    //     // Lấy danh sách uuid của tất cả chapter từ bản nháp và bản chính
    //     $allChapterUuids = collect($draftCourse->chapters)
    //         ->pluck('uuid')
    //         ->merge($mainCourse->chapters->pluck('uuid'))
    //         ->unique();

    //     foreach ($allChapterUuids as $uuid) {
    //         $draftChapter = $draftCourse->chapters->firstWhere('uuid', $uuid);
    //         $mainChapter = $mainCourse->chapters->firstWhere('uuid', $uuid);

    //         // $chapterDiff = [
    //         //     'uuid' => $uuid,
    //         //     'diff' => diffModels($draftChapter, $mainChapter),
    //         //     'lessons' => [],
    //         // ];
    //         $chapterDiff = diffModels($draftChapter, $mainChapter);

    //         $draftLessons = $draftChapter?->lessons ?? collect();
    //         $mainLessons = $mainChapter?->lessons ?? collect();

    //         $allLessonUuids = $draftLessons->pluck('uuid')
    //             ->merge($mainLessons->pluck('uuid'))
    //             ->unique();

    //         foreach ($allLessonUuids as $lessonUuid) {
    //             $draftLesson = $draftLessons->firstWhere('uuid', $lessonUuid);
    //             $mainLesson = $mainLessons->firstWhere('uuid', $lessonUuid);

    //             $chapterDiff['lessons'][$draftLesson->id ?? 'new_lesson'] = diffModels($draftLesson, $mainLesson);
    //         }

    //         // $chapterDiffs[$draftChapter->id ?? 'new_chapter'] = $chapterDiff;
    //         $chapterDiffs[$draftChapter->id ?? 'new_chapter'] = $mainChapter;
    //     }

    //     return $chapterDiffs;
    // }

    
    function edit(Request $request)
    {
        switch ($request->step) {
            // case '0':
            //     $currentCourse = Course::withoutGlobalScopes()->find($request->id);
            //     $courses = Course::withoutGlobalScopes()->where('uuid', $currentCourse->uuid)
            //         ->orderBy('created_at', 'desc')->get();
            //     // dd($courses);
            //     return view('frontend.instructor-dashboard.course.commits', [
            //         'title' => 'Commits',
            //         'courses' => $courses,
            //         'isCreateDraft' => $request->is_create_draft,
            //         'step' => 0
            //     ]);
            //     break;
            case '1':
                try{
                    DB::beginTransaction();

                    $course = Course::withoutGlobalScopesWithRelations()->find($request->id);

                    // Tìm bản chính cùng uuid
                    $original = Course::withoutGlobalScopesWithRelations()
                        ->when(
                            $course->uuid != null, 
                            fn($q) => $q->where('uuid', $course->uuid),
                            fn($q) => $q->where('id', $course->id)
                        )
                        ->where('is_published', true)
                        ->first();
                        
                    $diff = diffModels($course, $original);
                    
                    // Nếu course hiện tại đã được publish thì khi sửa sẽ tạo 1 bản nháp mới
                    if($course->is_published && $course->is_current && $request->is_create_draft){
                        $course = Course::currentWithoutRevisionWithRelations($request->id);
                        $newThumbnailPath = $this->reuploadFileFromPath($course->thumbnail);
                        $course->updateAsDraft([
                            'message_for_commit' => 'New draft created',
                            'title' => $course->title,
                            'slug' => $course->slug,
                            // 'thumbnail' => $course->thumbnail,
                            'thumbnail' => $newThumbnailPath,
                            'seo_description' => $course->seo_description,
                            'demo_video_storage' => $course->demo_video_storage,
                            'demo_video_source' => $course->demo_video_source,
                            'price' => $course->price,
                            'discount' => $course->discount,
                            'description' => $course->description,
                            'instructor_id' => $course->instructor_id,
                            // 'capacity' => $course->capacity,
                            // 'qna' => $course->qna,
                            'certificate' => $course->certificate,
                            'category_id' => $course->category_id,
                            'course_level_id' => $course->course_level_id,
                            'course_language_id' => $course->course_language_id,
                            'is_approved' => 'pending',
                        ]);
                        $courseDraft = $course->draft;
                        
                        foreach ($course->chapters as $chapter) {
                            $chapter->updateAsDraft([
                                'title' => $chapter->title,
                                'order' => $chapter->order,
                                'instructor_id' => $chapter->instructor_id,
                                'course_id' => $courseDraft->id,
                            ]); 
                            $chapterDraft = $chapter->draft;
                            
                            foreach ($chapter->lessons as $lesson) {
                                $lesson->updateAsDraft([
                                    'title' => $lesson->title,
                                    'slug' => $lesson->slug,
                                    'storage' => $lesson->storage,
                                    'file_path' => $lesson->file_path,
                                    'file_type' => $lesson->file_type,
                                    'duration' => $lesson->duration,
                                    'is_preview' => $lesson->is_preview,
                                    'downloadable' => $lesson->downloadable,
                                    'description' => $lesson->description,
                                    'instructor_id' => $lesson->instructor_id,
                                    'course_id' => $chapterDraft->course_id,
                                    'chapter_id' => $chapterDraft->id
                                ]);
                            }

                        }
                        $admin = Admin::find(1);
                        $admin->notify(new CourseDrafted($courseDraft, $courseDraft->instructor));

                        DB::commit();

                        notyf()->success('Create Draft Successfully!');

                        
                        return redirect()->route('instructor.courses.edit', [
                            'id' => $courseDraft->id,
                            'step' => 1,
                            'is_create_draft' => true
                        ]);
                    }

                    $categories = CourseCategory::where('status', 1)->get();
                    $levels = CourseLevel::all();
                    $languages = CourseLanguage::all();

                    DB::commit();
                    return view('frontend.instructor-dashboard.course.edit', [
                        'title' => 'Edit Course',
                        'categories' => $categories,
                        'levels' => $levels,
                        'languages' => $languages,
                        'course' => $course,
                        'isCreateDraft' => $request->is_create_draft,
                        'diff' => $diff ?? null
                    ]);
                    break;
                }catch(\Exception $e) {
                    DB::rollBack();
                    throw $e;
                    return redirect()->back();
                }

            // case '2':
            //     $course = Course::withDrafts()->where('id', $request->id)->first(); // Bản nháp
            //     // if($course->draft) {
            //     //     $course = $course->draft;
            //     // }
            //     $categories = CourseCategory::where('status', 1)->get();
            //     $levels = CourseLevel::all();
            //     $languages = CourseLanguage::all();
            //     return view('frontend.instructor-dashboard.course.more-info', compact('categories', 'levels', 'languages', 'course'));
            //     break;

            // case '3':
            case '2':
                $course = Course::withoutGlobalScopesWithRelations()->find($request->id);
                if($course->is_current) {
                    // $course = Course::currentWithoutRevision($request->id);
                    $chapters = CourseChapter::with(['lessons' => fn($q) => $q->current()])
                    ->where('course_id', $course->id)
                    ->orderBy('order')
                    ->current()
                    ->get();
                }
                else{
                    $chapters = CourseChapter::withoutGlobalScopes()
                        ->with(['lessons' => fn($q) => $q->withoutGlobalScopes()])
                        ->where('course_id', $course->id)->orderBy('order')->get();
                }

                $isCourseHasDraft = $course->where('uuid', $course->uuid)->exists();
                // Tìm bản chính cùng uuid
                $original = Course::withoutGlobalScopesWithRelations()
                    ->when($isCourseHasDraft, fn($q) => $q->where('is_published', true)) 
                    ->when(
                        $course->uuid != null, 
                        fn($q) => $q->where('uuid', $course->uuid),
                        fn($q) => $q->where('id', $course->id)
                    )
                    ->first();
                // dd($original, $course);
                
                $diff = compareChaptersWithNestedLessons($course, $original);
                // dd($diff);
                
                return view('frontend.instructor-dashboard.course.course-content-edit', [
                    'title' => 'Edit Course',
                    'course' => $course,
                    'isCurrent' => $course->is_current,
                    'isCreateDraft' => $request->is_create_draft,
                    'chapters' => $chapters,
                    'diff' => $diff ?? null
                ]);
                break;

            case '3':
                try{
                    DB::beginTransaction();

                    $course = Course::currentWithoutRevisionWithRelations($request->id);
                    // Nếu course hiện tại ko phải bản nháp chỉnh sửa
                    /**flatMap(): gộp toàn bộ lesson từ tất cả các chapter thành một collection phẳng.
                        sum('duration'): tính tổng thời lượng của tất cả lesson gộp lại. */
                    $course->update([
                        'duration' => $course->chapters
                            ->flatMap(fn($chapter) => $chapter->lessons)
                            ->sum('duration')
                    ]);

                    // $admin = Admin::find(1);
                    // $admin->notify(new CourseUpdated($course, $course->instructor));

                    DB::commit();
                    return redirect()->route('instructor.courses.index');

                }catch(\Exception $e){
                    DB::rollBack();
                    throw $e;
                }
                break;
        }
    }

    function update(Request $request)
    {
        switch ($request->current_step) {
            case '1':
                $rules = [
                    'message_for_commit' => ['required', 'max:255', 'string'],
                    'title' => ['required', 'max:255', 'string'],
                    'seo_description' => ['nullable', 'max:255', 'string'],
                    'demo_video_storage' => ['nullable', 'in:youtube,vimeo,external_link,upload', 'string'],
                    'price' => ['required', 'numeric'],
                    'discount' => ['nullable', 'numeric'],
                    'description' => ['required'],
                    'demo_video_source' => ['nullable'],
                    'certificate' => ['nullable', 'boolean'],
                    'category' => ['required', 'integer'],
                    'level' => ['required', 'integer'],
                    'language' => ['required', 'integer'],
                ];
                $request->validate($rules);

                try{
                    DB::beginTransaction();

                    $course = Course::withoutGlobalScopes()->find($request->id);
                    if($course->is_current) {
                        $course = Course::currentWithoutRevision($request->id);
                    }

                    if ($request->hasFile('thumbnail')) {
                        $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
                        $this->deleteFile($course->thumbnail);
                    }
                    else{
                        $thumbnailPath = $course->thumbnail;
                    }
                    

                    // if($course->is_published) {
                    //     // Nếu bản chính chưa có bản nháp thì tạo 1 bản nháp mới
                    //     $course->updateAsDraft([
                    //         'title' => $request->title,
                    //         'slug' => Str::slug($request->title),
                    //         'thumbnail' => $thumbnailPath,
                    //         'seo_description' => $request->seo_description,
                    //         'demo_video_storage' => $request->demo_video_storage,
                    //         'demo_video_source' => $request->filled('file') ? $request->file : $request->url,
                    //         'price' => $request->price,
                    //         'discount' => $request->discount,
                    //         'description' => $request->description,
                    //         'instructor_id' => Auth::guard('web')->user()->id,
                    //         'capacity' => $request->capacity,
                    //         'qna' => $request->qna ? 1 : 0,
                    //         'certificate' => $request->certificate ? 1 : 0,
                    //         'category_id' => $request->category,
                    //         'course_level_id' => $request->level,
                    //         'course_language_id' => $request->language,
                    //     ]);
                    // }
                    // else{
                        // Bản nháp đã có sẵn
                        // $course = $course->draft; 
                        $course->update([
                            'message_for_commit' => $request->message_for_commit,
                            'title' => $request->title,
                            'slug' => Str::slug($request->title),
                            'seo_description' => $request->seo_description,
                            'thumbnail' => $thumbnailPath,
                            'demo_video_storage' => $request->demo_video_storage,
                            'demo_video_source' => $request->filled('file') ? $request->file : $request->url,
                            'price' => $request->price,
                            'discount' => $request->discount,
                            'description' => $request->description,
                            'instructor_id' => Auth::guard('web')->user()->id,
                            // 'capacity' => $request->capacity,
                            // 'qna' => $request->qna ? 1 : 0,
                            'certificate' => $request->certificate ? 1 : 0,
                            'category_id' => $request->category,
                            'course_level_id' => $request->level,
                            'course_language_id' => $request->language,
                            'is_published' => false,
                            'is_approved' => 'pending',
                            'message_for_rejection' => null
                        ]);
                    // }
                    
                    $admin = Admin::find(1);
                    $admin->notify(new CourseUpdated($course, auth('web')->user()));

                    // save course id on session
                    Session::put('course_create_id', $course->id);

                    DB::commit();
    
                    return response([
                        'status' => 'success',
                        'message' => 'Updated successfully.',
                        'redirect' => route('instructor.courses.edit', [
                            'id' => $course->id, 
                            'step' => $request->next_step, 
                            'is_create_draft' => $request->is_create_draft
                        ])
                        // 'redirect' => route('instructor.courses.edit', ['id' => $draft->id, 'step' => $request->next_step])
                    ]);
                }
                catch(\Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage());
                    return response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);

                }

                break;

            // case '2':
            //     // validation
            //     $request->validate([
            //         'capacity' => ['nullable', 'numeric'],
            //         'qna' => ['nullable', 'boolean'],
            //         'certificate' => ['nullable', 'boolean'],
            //         'category' => ['required', 'integer'],
            //         'level' => ['required', 'integer'],
            //         'language' => ['required', 'integer'],
            //     ]);

            //     try{
            //         DB::beginTransaction();
            //         // update course data
            //         $course = Course::find($request->id);
            //         // $draft = $course->saveAsDraft([
            //         //     'capacity' => $request->capacity,
            //         //     'qna' => $request->qna ? 1 : 0,
            //         //     'certificate' => $request->certificate ? 1 : 0,
            //         //     'category_id' => $request->category,
            //         //     'course_level_id' => $request->level,
            //         //     'course_language_id' => $request->language
            //         // ]);
            //         $course->fill([
            //             'capacity' => $request->capacity,
            //             'qna' => $request->qna ? 1 : 0,
            //             'certificate' => $request->certificate ? 1 : 0,
            //             'category_id' => $request->category,
            //             'course_level_id' => $request->level,
            //             'course_language_id' => $request->language
            //         ]);
            //         $course->save();
            //         DB::commit();

            //         return response([
            //             'status' => 'success',
            //             'message' => 'Updated successfully.',
            //             'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
            //         ]);
            //     }catch(\Exception $e){
            //         DB::rollBack();
            //         Log::error($e);
            //     }

            //     break;
            // case '3':
            case '2':
                return response([
                    'status' => 'success',
                    'message' => 'Updated successfully.',
                    'redirect' => route('instructor.courses.edit', ['id' => $request->id, 'step' => $request->next_step])
                ]);
                break;

            // case '3':
            //     $course = Course::withoutGlobalScopes()->find($request->id);
            //     /**flatMap(): gộp toàn bộ lesson từ tất cả các chapter thành một collection phẳng.
            //         sum('duration'): tính tổng thời lượng của tất cả bài học gộp lại. */
            //     $course->duration = $course->chapters
            //         ->flatMap(fn($chapter) => $chapter->lessons)
            //         ->sum('duration');
            //     $course->save();
            //     return redirect()->route('instructor.courses.index');
            //     break;
        }
    }

    public function destroy(Request $request)
    {
        $course = Course::findOrFail($request->id);
        $course->delete();
        return response([
            'status' => 'success',
            'message' => 'Deleted successfully.',
            'redirect' => route('instructor.courses.index')
        ]);
    }
}
