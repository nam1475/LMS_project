<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseChapterLession;
use App\Traits\FileUpload;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseContentController extends Controller
{
    use FileUpload;

    function createChapterModal(string $id): String
    {
        return view('frontend.instructor-dashboard.course.partials.course-chapter-modal', compact('id'))->render();
    }

    function storeChapter(Request $request, string $courseId): RedirectResponse
    {

        $request->validate([
            'title' => ['required', 'max:255'],
        ]);
        
        $course = Course::currentWithoutRevision($courseId);
        $course->chapters()->create([
            'title' => $request->title,
            'instructor_id' => auth('web')->user()->id,
            'order' => CourseChapter::withoutGlobalScopes()->where('course_id', $courseId)->count() + 1,
            'is_published' => false,
            'course_id' => $courseId
        ]);

        notyf()->success('Created Success fully');

        return redirect()->back();
    }

    function createLesson(Request $request): String
    {
        $course = Course::withoutGlobalScopes()->find($request->course_id);
        $chapterId = $request->chapter_id;
        return view('frontend.instructor-dashboard.course.partials.chapter-lesson-modal', [
            'course' => $course,
            'chapterId' => $chapterId,
            'isCreateDraft' => $request->is_create_draft,
            'diff' => null
        ])->render();
    }

    function storeLesson(Request $request): RedirectResponse
    {
        try{

            $rules = [
                'title' => ['required', 'string', 'max:255'],
                'source' => ['required', 'string'],
                'file_type' => ['required', 'in:video,audio,file,pdf,doc,link'],
                'is_preview' => ['nullable', 'boolean'],
                'downloadable' => ['nullable', 'boolean'],
                'description' => ['required']
            ];
            if ($request->filled('file')) {
                $rules['file'] = ['required'];
            } else {
                $rules['url'] = ['required'];
            }
            $request->validate($rules);
            
            DB::beginTransaction();
            
            $chapter = CourseChapter::currentWithoutRevision($request->chapter_id);
            $chapter->lessons()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'storage' => $request->source,
                'file_path' => $request->filled('file') ? $request->file : $request->url,
                'file_type' => $request->file_type,
                'duration' => $request->duration,
                'is_preview' => $request->filled('is_preview') ? 1 : 0,
                'downloadable' => $request->filled('downloadable') ? 1 : 0,
                'description' => $request->description,
                'instructor_id' => Auth::user()->id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'order' => CourseChapterLession::withoutGlobalScopes()->where('chapter_id', $request->chapter_id)->count() + 1,
                'is_published' => false
            ]);
    
            DB::commit();
            notyf()->success('Created Success fully');
            return redirect()->back();
        }catch (Exception $e) {
            DB::rollBack();
            throw $e;
            return redirect()->back();
        }
    }

    function editChapterModal(string $id): String
    {
        $editMode = true;
        $chapter = CourseChapter::currentWithoutRevision($id);

        return view('frontend.instructor-dashboard.course.partials.course-chapter-modal', compact('chapter', 'editMode'))->render();
    }

    function updateChapterModal(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $chapter = CourseChapter::currentWithoutRevision($id);
        $chapter->title = $request->title;
        $chapter->save();
        
        notyf()->success('Updated Susccessfully!');
        return redirect()->back();
    }

    function destroyChapter(string $id): Response
    {
        try {
            // delete chapter
            $chapter = CourseChapter::currentWithoutRevision($id);
            $chapter->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Course Level Error >> " . $e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }

    function editLesson(Request $request): String
    {
        $editMode = true;
        $course = Course::withoutGlobalScopes()->find($request->course_id);
        $chapterId = $request->chapter_id;
        $lessonId = $request->lesson_id;
        $lesson = CourseChapterLession::withoutGlobalScopes()->find($lessonId);
        $isNewLesson = CourseChapterLession::withoutGlobalScopes()->where('uuid', $lesson->uuid)->count();
        if($isNewLesson > 1){
            $originalLesson = CourseChapterLession::withoutGlobalScopes()->where('uuid', $lesson->uuid)
                ->where('is_published', true)
                ->first();
            $diff = diffModels($lesson, $originalLesson);
        }
        // dd($diff);

        return view('frontend.instructor-dashboard.course.partials.chapter-lesson-modal',
            [
                'editMode' => $editMode,
                'course' => $course,
                'chapterId' => $chapterId,
                'lesson' => $lesson,
                'isCreateDraft' => $request->is_create_draft,
                'diff' => $diff ?? null

            ]
        )->render();
    }

    function updateLesson(Request $request, string $id): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string'],
            'file_type' => ['required', 'in:video,audio,file,pdf,doc'],
            'is_preview' => ['nullable', 'boolean'],
            'downloadable' => ['nullable', 'boolean'],
            'description' => ['required']
        ];
        if ($request->filled('file')) {
            $rules['file'] = ['required'];
        } else {
            $rules['url'] = ['required'];
        }
        $request->validate($rules);

        $lesson = CourseChapterLession::currentWithoutRevision($id);
        $lesson->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'storage' => $request->source,
            'file_path' => $request->filled('file') ? $request->file : $request->url,
            'file_type' => $request->file_type,
            'duration' => $request->duration,
            'is_preview' => $request->filled('is_preview') ? 1 : 0,
            'downloadable' => $request->filled('downloadable') ? 1 : 0,
            'description' => $request->description,
            'instructor_id' => Auth::user()->id,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id
        ]);

        notyf()->success('Updated Success fully!');

        return redirect()->back();
    }

    function destroyLesson(string $id): Response
    {
        try {
            $lesson =  CourseChapterLession::currentWithoutRevision($id);
            $lesson->delete();
            notyf()->success('Deleted Successfully!');
            return response(['message' => 'Deleted Successfully!'], 200);
        } catch (Exception $e) {
            logger("Course Level Error >> " . $e);
            return response(['message' => 'Something went wrong!'], 500);
        }
    }

    /** Sort chapter lessons */
    function sortLesson(Request $request, string $chapterId) {
        $newOrders = $request->order_ids;
        foreach($newOrders as $key => $itemId) {
            $lesson = CourseChapterLession::where(['chapter_id' => $chapterId, 'id' => $itemId])
                ->current()->first()->withoutRevision();
            $lesson->order = $key + 1;
            $lesson->save();
        }

        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }

    /** return sort chapter list */
    function sortChapter(string $id) : string {
        $chapters = CourseChapter::where('course_id', $id)->orderBy('order')
            ->current()->get();

        return view('frontend.instructor-dashboard.course.partials.course-chapter-sort-modal', compact('chapters'))->render();
    }

    function updateSortChapter(Request $request, string $id) {
        $newOrders = $request->order_ids;
        foreach($newOrders as $key => $itemId) {
            $lesson = CourseChapter::where(['course_id' => $id, 'id' => $itemId])
                ->current()->first()->withoutRevision();
            $lesson->order = $key + 1;
            $lesson->save();
        }

        return response(['status' => 'success', 'message' => 'Updated Successfully!']);
    }
}
