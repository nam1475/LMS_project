<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NewInstructorRequest;
use App\Traits\FileUpload;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Pest\Support\Str;

class RegisteredUserController extends Controller
{
    use FileUpload;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try{
            DB::beginTransaction();

            if($request->type === 'student'){
                $user = User::create([
                    'name' => $request->name,   
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'student',
                    'remember_token' => Str::random(60),
                    'approve_status' => 'approved'
                ]);
            }elseif($request->type === 'instructor') {
                if($request->hasFile('document')){
                    $request->validate(['document' => ['mimes:pdf,doc,docx,jpg,png', 'max:12000']]);
                    $filePath = $this->uploadFile($request->file('document'));
                }

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'student',
                    'remember_token' => Str::random(60),
                    'approve_status' => 'pending',
                    'document' => $filePath ?? null
                ]);
                
                $admin = Admin::find(1);
                $admin->notify(new NewInstructorRequest($user));
            }else {
                abort(404);
            }

            event(new Registered($user));

            Auth::guard('web')->login($user);

            DB::commit();

            return redirect()->route('verification.notice');
            // if($request->user()->role == 'student') {
            //     return redirect()->intended(route('student.dashboard', absolute: false));
            // }elseif($request->user()->role == 'instructor') {
            //     return redirect()->intended(route('instructor.dashboard', absolute: false));
            // }
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
