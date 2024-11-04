<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Mail\VerifyEmail;

class EmailVerificationController extends Controller
{
    public function confirmEmail(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        if ($user) {
            // Update the email_verified_at column
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => now()]);
        }

        // Redirect to the login page
        // return redirect()->route('login');
        return redirect()->route('verification.notice');

    }
    public function resend(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $user = Auth::user();

        // Check if the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 400);
        }

        // Create the verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return response()->json(['message' => 'Verification link sent.'], 200);
    }

    public function verified()
    {
    return view('email-confirmed');
    }
}

