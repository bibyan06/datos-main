<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthLoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        // $this->middleware('verify.email.and.move.to.employee');
        
        // Validate the request data
        $request->validate([
            'employee_id' => 'required|string',
            'password' => 'required|string',
        ]);
       
        $credentials = $request->only('employee_id', 'password');
        $employee = Employee::where('employee_id', $request->employee_id)->first();
       
        if ($employee && Auth::attempt($credentials)) {
            // Authentication was successful
            $this->updateLoginSession(Auth::user()->employee_id);
            return $this->sendLoginResponse($request);
        }else if(!Auth::attempt($credentials)){
            return redirect()->back()->with('error', 'Invalid Employee ID or Password. Please try again!')
                ->with('email', $request->employee_id);
        }else if(!$employee) {
            return redirect()->back()->with('error', 'Please verify your account first!')
                ->with('email', $request->employee_id);

           
        }
        

       
        


        // Authentication failed
        return redirect()->back()->with('error', 'Invalid employee ID or password.');
    }

    protected function updateLoginSession($employeeId)
    {
        $existingSession = DB::table('login_session')
            ->where('employee_id', $employeeId)
            ->first();

        if ($existingSession) {
            // Update existing session
            DB::table('login_session')
                ->where('employee_id', $employeeId)
                ->update([
                    'login_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'status' => 'active',
                ]);
        } else {
            // Create new session
            DB::table('login_session')->insert([
                'employee_id' => $employeeId,
                'login_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'status' => 'active',
            ]);
        }
    }

    public function loginverified(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = auth()->user();

            // Check if email is verified
            if ($user->hasVerifiedEmail()) {
                // Update email_verified_at timestamp
                $user->email_verified_at = now();
                $user->save();

                // Move user data to employees table
                $employee = new Employee();
                $employee->name = $user->name;
                $employee->email = $user->email;
                // Add other fields as needed
                $employee->save();
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        // Regenerate session
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        // Get the authenticated user
        $user = auth()->user();
        $employeeId = $user->employee_id;

        // Log employee ID for debugging
        \Log::info("Employee ID during login: " . $employeeId);

        // Split the employee_id by a delimiter (e.g., a dash or other character)
        // Assuming the pattern is something like "001-XXX-XXX"
        $segments = explode('-', $employeeId);

        // Ensure the second segment exists and check it
        if (isset($segments[1])) {
            $secondSegment = $segments[1];

            \Log::info("Second Segment: " . $secondSegment);

            // Redirect based on the second segment
            if ($secondSegment === '001') {
                \Log::info("Redirecting to Admin Home");
                return redirect()->route('home.admin');
            } elseif ($secondSegment === '002') {
                \Log::info("Redirecting to Office Staff Home");
                return redirect()->route('home.office_staff');
            } elseif ($secondSegment === '003') {
                \Log::info("Redirecting to Dean Home");
                return redirect()->route('home.dean');
            }
        }

        // Default fallback if the pattern does not match
        \Log::info("Redirecting to default /home");
        return redirect()->intended($this->redirectPath());  // Default fallback to /home
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );

            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors(['error' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.']);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 3
        );
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input($this->username())).'|'.$request->ip();
    }

    public function username()
    {
        return 'employee_id';
    }


    public function logout(Request $request)
    {
        // Get the current user ID
        $user = Auth::user();
        $employeeId = $user->employee_id;

        // Log the values for debugging
        \Log::info("Attempting to update login_session for employee_id: $employeeId");
        \Log::info("Logout Date: " . Carbon::now()->format('Y-m-d H:i:s'));

        // Update the login_session table
        DB::table('login_session')
            ->where('employee_id', $employeeId)
            ->where('status', 'active')
            ->update([
                'logout_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'status' => 'inactive'
            ]);

        // Log out the user
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
