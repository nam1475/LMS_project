<?php

namespace App\Http\Controllers\Frontend;

use App\Events\SendComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapterLession;
use App\Models\LessonComment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LessonCommentController extends Controller
{
    function sendComment(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            // $course = Course::withoutGlobalScopes()->findOrFail($id);
            $lesson = CourseChapterLession::withoutGlobalScopes()->findOrFail($id);
            $user = auth('web')->user();
            // $comment = $lesson->comments()->create([
            //     'user_id' => $user->id,
            //     'comment' => $request->comment,
            // ]);      
            // $comment = $lesson->comment($request->comment);    
            if($request->comment_id){
                $parentComment = LessonComment::find($request->comment_id);
                $comment = LessonComment::create([
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                    'parent_id' => $request->comment_id,
                    'lesson_id' => $lesson->id
                ]);
                $totalComments = $parentComment->childComments()->count();
            }
            else{
                $comment = $lesson->comments()->create([
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                ]);    
                $totalComments = $lesson->comments()->count();
            }
            
            $isReplied = $request->comment_id ? true : false;
    
            event(new SendComment($comment->comment, $user, $totalComments, 
                $isReplied, $comment->lesson_id, $request->comment_id ?? null));

            DB::commit();
    
            return response(['status' => 'success', 'message' => 'Comment sent successfully', 'commentId' => $request->comment_id ?? '']);
        }catch(\Exception $e){
            DB::rollBack();
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    function fetchComments(Request $request, $id)
    {
        $user = auth('web')->user();
        // $course = Course::withoutGlobalScopes()->findOrFail($id);
        $lesson = CourseChapterLession::withoutGlobalScopes()->findOrFail($id);
        if($request->comment_id){
            $comments = LessonComment::with(['commentator', 'childComments'])
                ->where(['id' => $request->comment_id, 'parent_id' => null])->first();
        }
        else{
            $comments = $lesson->comments()->with('commentator')->where('parent_id', null)
                            ->orderBy('created_at', 'asc')->get();
        }
        return response(['status' => 'success', 'comments' => $comments]);
    }
}
