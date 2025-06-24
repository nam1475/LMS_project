<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InstructorRequestApprovedMail;
use App\Mail\InstructorRequestRejectMail;
use App\Models\User;
use App\Notifications\InstructorRequestRejected;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InstructorRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $instructorsRequests = User::
            when($request->has('search') && $request->filled('search'), function($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->has('status') && $request->filled('status'), function($query) use ($request) {
                $query->where('approve_status', $request->status);
            })
            ->where(function($query) {
                $query->where('approve_status', 'pending')
                    ->orWhere('approve_status', 'rejected');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);
        return view('admin.instructor-request.index', compact('instructorsRequests'));
    }

    // function rejectApprovalModal($courseId){
    //     return view('admin.course.course-module.partials.reject-modal', compact('courseId'))->render();
    // }

    // function sendRejectApproval(Request $request, string $id){
    //     $user = User::find($id);
    //     $user->update([
    //         'message_for_rejection' => $request->message,
    //         'approve_status' => 'rejected',
    //     ]);
    //     $user->notify(new InstructorRequestRejected($request->message));
        
    //     notyf()->success('Send message successfully.');

    //     return response(['status' => 'success']);
    // }
    
    function download(User $user)
    {
        $path = public_path($user->document);
        return response()->file($path);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $instructor_request)
    {
        $request->validate(['status' => ['required', 'in:approved,rejected,pending']]);
        try{
            DB::beginTransaction();
            self::sendNotification($instructor_request);
            $instructor_request->approve_status = $request->status;
            $request->status == 'approved' ? $instructor_request->role = 'instructor' : "";
            $instructor_request->message_for_rejection = null;
            $instructor_request->save();
            // dd(123);
            
            DB::commit();
            // return redirect()->back();
            return response(['status' => 'success', 'message' => 'Updated Successfully!']);
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
            return response(['status' => 'error']);
        }
    }

    public static function sendNotification($instructor_request): void
    {
        switch ($instructor_request->approve_status) {
            case 'approved':
                try{
                    DB::beginTransaction();
                    if (config('mail_queue.is_queue')) {
                        Mail::to($instructor_request->email)->queue(new InstructorRequestApprovedMail());
                    } else {
                        Mail::to($instructor_request->email)->send(new InstructorRequestApprovedMail());
                    }
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollBack();
                    throw $e;
                }
                break;
            case 'rejected':
                try{
                    if (config('mail_queue.is_queue')) {
                        Mail::to($instructor_request->email)->queue(new InstructorRequestRejectMail());
                    } else {
                        Mail::to($instructor_request->email)->send(new InstructorRequestRejectMail());
                    }
                }catch(\Exception $e){
                    throw $e;
                }
                break;
        }
    }
}
