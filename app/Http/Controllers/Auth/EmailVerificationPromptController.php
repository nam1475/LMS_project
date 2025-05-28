<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        return $user->hasVerifiedEmail()
                    ? redirect()->intended($user->role == 'student' ? route('student.dashboard', absolute: false) : route('instructor.dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
