<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Show the email verification notice.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  string  $hash
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->email))) {
            throw new AuthorizationException;
        }

        $user->markEmailAsVerified();
        $segments = explode('-', $user->employee_id);

            // Determine the role based on the second segment
            $position = null;
            switch ($segments[1]) {
                case '001':
                    $position = 'Admin';
                    break;
                case '002':
                    $position = 'Office_staff';
                    break;
                case '003':
                    $position = 'Dean';
                    break;
                default:
                    Log::error('Invalid employee ID format', ['employee_id' => $user->employee_id]);
                    return back()->withErrors(['employee_id' => 'Invalid employee ID format.'])->withInput();
            }
        // Create employee record
        Employee::create([
            'employee_id' => $user->employee_id,
            'last_name' => $user->last_name,
            'first_name' => $user->first_name,
            'position' => $position, // Assign position
        ]);

        Log::info('Employee record created successfully');

        DB::commit();
        return Redirect::route('email.confirmed');
    }


    
}
