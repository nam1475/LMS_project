<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseBasicInfoCreateRequest;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseChapter;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;
use App\Models\User;
use App\Notifications\CourseRejected;
use App\Traits\FileUpload;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use FileUpload;

    function index(): View
    {
        // $courses = Course::with(['instructor'])->withDrafts()
        //         ->where('instructor_id', Auth::guard('web')->user()->id)
        //         ->orderBy('updated_at', 'DESC')->paginate(25);
        $courses = Course::withoutGlobalScopes()->where([
            'instructor_id' => Auth::guard('web')->user()->id, 
            ])->where(function ($query) {
                $query->where(function ($q) {
                    $q->where(['is_published' => true, 'is_current' => true]);
                })
                ->orWhere(function ($q) {
                    $q->where(['is_published' => false, 'is_current' => true]);
                });
            })
            ->orderBy('updated_at', 'DESC')->paginate(25);
        return view('admin.course.course-module.index', compact('courses'));
    }

    function showCommits($id): View
    {
        $currentCourse = Course::withoutGlobalScopes()->find($id);
        $courseVersions = Course::withoutGlobalScopes()->where('uuid', $currentCourse->uuid)
            ->orderBy('created_at', 'desc')->get();
        // dd($courseVersions);
        return view('admin.course.course-module.commits', [
            'title' => 'Commits',
            'courses' => $courseVersions,
            'isCommits' => true
        ]);
    }

    function rejectApprovalModal($courseId){
        return view('admin.course.course-module.partials.reject-modal', compact('courseId'))->render();
    }

    function sendRejectApproval(Request $request, string $id){
        $course = Course::withoutGlobalScopes()->find($id);
        $course->withoutRevision();
        $course->update([
            'message_for_rejection' => $request->message,
            'is_approved' => 'rejected',
        ]);
        
        notyf()->success('Send message successfully.');
        
        $course->instructor->notify(new CourseRejected($course, $course->instructor));
        
        return redirect()->back();
    }

    /** change approve status */
    function updateApproval(Request $request, string $id) : Response{
        try{
            DB::beginTransaction();
            $course = Course::withoutGlobalScopes()->find($id);

            // if($request->status == 'rejected'){
            //     $course->update([
            //         'message_for_rejection' => $request->message,
            //         'is_approved' => $request->status,
            //     ]);
            //     DB::commit();
            //     return response(['status' => 'success', 'message' => 'Send message successfully.']);
            // }
            
            if($course->is_current){
                $course = Course::currentWithoutRevisionWithRelations($id);
                if ($request->status == 'approved') {
                    $course->update(['is_approved' => 'approved']);
                    $course->publishWithRelations();
                    // dd(123);
                    // $publishedCourse->save();
                    // return response(['status' => 'success', 'message' => $publishedCourse]);
                    // if ($publishedCourse) {
                    //     // Lấy lại bản chính vừa publish 
                    //     $course = Course::where('uuid', $publishedCourse->uuid)->current()->first()->withoutRevision();
                    //     $course->update(['is_approved' => 'approved']);
                    // }
                }
                else{
                    $course->update(['is_approved' => $request->status]);
                }
            }
            else{
                $course->update(['is_approved' => $request->status]);
            }
            
            DB::commit();
            return response(['status' => 'success', 'message' => 'Updated successfully.']);
        }catch(\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    function create(): View
    {
        $instructors = User::where('role', 'instructor')
            ->where('approve_status', 'approved')->get();
        return view('admin.course.course-module.create', compact('instructors'));
    }

    function storeBasicInfo(CourseBasicInfoCreateRequest $request)
    {
        $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        $course = new Course();
        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->seo_description = $request->seo_description;
        $course->thumbnail = $thumbnailPath;
        $course->demo_video_storage = $request->demo_video_storage;
        $course->demo_video_source = $request->demo_video_source;
        $course->price = $request->price;
        $course->discount = $request->discount;
        $course->description = $request->description;
        $course->instructor_id = $request->instructor;
        $course->save();

        // save course id on session
        Session::put('course_create_id', $course->id);

        return response([
            'status' => 'success',
            'message' => 'Create course successfully.',
            'redirect' => route('admin.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
        ]);
    }

    function edit(Request $request)
    {
        switch ($request->step) {
            case '1':
                $categories = CourseCategory::where('status', 1)->get();
                $levels = CourseLevel::all();
                $languages = CourseLanguage::all();
                
                $course = Course::withoutGlobalScopes()->find($request->id);
                $original = Course::withoutGlobalScopes()->with([
                        'chapters' => fn($q) => $q->withoutGlobalScopes(),
                        'chapters.lessons' => fn($q) => $q->withoutGlobalScopes(),
                        ])
                        ->where('uuid', $course->uuid)
                        ->where('is_published', true)
                        ->first();
                
                $diff = diffModels($course, $original);
                return view('admin.course.course-module.edit', compact('course', 'categories', 'levels', 'languages', 'diff'));
                break;

            case '2':
                $course = Course::withoutGlobalScopes()->with([
                        'chapters' => fn($q) => $q->withoutGlobalScopes()->orderBy('order'),
                        'chapters.lessons' => fn($q) => $q->withoutGlobalScopes()->orderBy('order'),
                    ])->find($request->id);
                // if($course->is_current){
                //     $chapters = CourseChapter::with(['lessons' => fn($q) => $q->current()])
                //             ->where('course_id', $course->id)
                //             ->orderBy('order')
                //             ->current()
                //             ->get();
                // }
                // else{
                    // $chapters = CourseChapter::withoutGlobalScopes()
                    //         ->with(['lessons' => fn($q) => $q->withoutGlobalScopes()])
                    //         ->where('course_id', $course->id)
                    //         ->orderBy('order')
                    //         ->get();
                // }
                $original = Course::withoutGlobalScopes()->with([
                    'chapters' => fn($q) => $q->withoutGlobalScopes()->orderBy('order'),
                    'chapters.lessons' => fn($q) => $q->withoutGlobalScopes()->orderBy('order'),
                    ])
                    ->where('uuid', $course->uuid)
                    ->where('is_published', true)
                    ->first();
                        
                $diff = compareChaptersWithNestedLessons($course, $original);

                return view('admin.course.course-module.course-content', [
                    'course' => $course,
                    // 'chapters' => $chapters,
                    'diff' => $diff
                ]);
                break;

            // case '4':
            //     $courseId = $request->id;
            //     $course = Course::findOrFail($request->id);
            //     $editMode = true;
            //     return view('admin.course.course-module.finish', compact('course', 'editMode'));
            //     break;
        }
    }

    function update(Request $request)
    {
        // dd($request->all());
        switch ($request->current_step) {
            case '1':
                $rules = [
                    'title' => ['required', 'max:255', 'string'],
                    'seo_description' => ['nullable', 'max:255', 'string'],
                    'demo_video_storage' => ['nullable', 'in:youtube,vimeo,external_link,upload', 'string'],
                    'price' => ['required', 'numeric'],
                    'discount' => ['nullable', 'numeric'],
                    'description' => ['required'],
                    'thumbnail' => ['nullable', 'image', 'max:3000'],
                    'demo_video_source' => ['nullable']
                ];

                $request->validate($rules);

                $course = Course::findOrFail($request->id);

                if ($request->hasFile('thumbnail')) {
                    $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
                    $this->deleteFile($course->thumbnail);
                    $course->thumbnail = $thumbnailPath;
                }

                $course->title = $request->title;
                $course->slug = Str::slug($request->title);
                $course->seo_description = $request->seo_description;
                $course->demo_video_storage = $request->demo_video_storage;
                $course->demo_video_source = $request->filled('file') ? $request->file : $request->url;
                $course->price = $request->price;
                $course->discount = $request->discount;
                $course->description = $request->description;
                $course->instructor_id = $course->instructor->id;
                $course->save();

                // save course id on session
                Session::put('course_create_id', $course->id);

                return response([
                    'status' => 'success',
                    'message' => 'Updated successfully.',
                    'redirect' => route('admin.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                ]);

                break;

            case '2':
                // validation
                $request->validate([
                    'capacity' => ['nullable', 'numeric'],
                    'duration' => ['required', 'numeric'],
                    'qna' => ['nullable', 'boolean'],
                    'certificate' => ['nullable', 'boolean'],
                    'category' => ['required', 'integer'],
                    'level' => ['required', 'integer'],
                    'language' => ['required', 'integer'],
                ]);

                // update course data
                $course = Course::findOrFail($request->id);
                $course->capacity = $request->capacity;
                $course->duration = $request->duration;
                $course->qna = $request->qna ? 1 : 0;
                $course->certificate = $request->certificate ? 1 : 0;
                $course->category_id = $request->category;
                $course->course_level_id = $request->level;
                $course->course_language_id = $request->language;
                $course->save();

                return response([
                    'status' => 'success',
                    'message' => 'Updated successfully.',
                    'redirect' => route('admin.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                ]);

                break;
            case '3':
                return response([
                    'status' => 'success',
                    'message' => 'Updated successfully.',
                    'redirect' => route('admin.courses.edit', ['id' => $request->id, 'step' => $request->next_step])
                ]);
                break;

            case '4':
                // validation
                $request->validate([
                    'message' => ['nullable', 'max:1000', 'string'],
                    'status' => ['required', 'in:active,inactive,draft']
                ]);

                // update course data
                $course = Course::findOrFail($request->id);
                $course->message_for_reviewer = $request->message;
                $course->status = $request->status;
                $course->save();
                return response([
                    'status' => 'success',
                    'message' => 'Updated successfully.',
                    'redirect' => route('admin.courses.index')
                ]);
                break;
        }
    }
}
