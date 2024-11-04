<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role; // Include the Role model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register(Request $request)
    {
        Log::info('Registration process started');

        if ($request->has('_token')) {
            Log::info('CSRF Token present in request: ' . $request->input('_token'));
        } else {
            Log::error('CSRF Token missing in request.');
        }

        // Allowed email domains
        $allowedDomains = ['bicol-u.edu.ph', 'gmail.com'];

        // Validation rules
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'age' => 'required|integer|min:18|max:120',
            'gender' => 'required|string|in:Male,Female',
            'phone_number' => 'required|digits:11',
            'home_address' => 'required|string|max:255',
            'employee_id' => [
                'required',
                'string',
                'max:20',
                'unique:users,employee_id',
                'regex:/^\d{4}-(001|002|003)-\d{4}$/',  
            ],
            'username' => 'required|string|max:20|unique:users',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
                function ($attribute, $value, $fail) use ($allowedDomains) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!in_array($domain, $allowedDomains)) {
                        $fail("The email must be from one of the following domains: " . implode(', ', $allowedDomains));
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:18',
                'confirmed',
                'regex:/[a-z]/',   // At least one lowercase letter
                'regex:/[A-Z]/',   // At least one uppercase letter
            ],
            'password_confirmation' => 'required|string|min:8',
        ]);

        Log::info('Validation passed', ['data' => $request->all()]);
        
        try {
            DB::beginTransaction();

            // Extract the segments from employee_id
            $employeeId = $request->employee_id;
            Log::info('Employee ID extracted', ['employee_id' => $employeeId]);
            $segments = explode('-', $employeeId);

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
                    Log::error('Invalid employee ID format', ['employee_id' => $employeeId]);
                    return back()->withErrors(['employee_id' => 'Invalid employee ID format.'])->withInput();
            }

            Log::info('Position determined', ['position' => $position]);

            // Retrieve the role ID from the roles table
            $role = Role::where('position', $position)->first();
            if (!$role) {
                Log::error('Role not found for position', ['position' => $position]);
                return back()->withErrors(['role' => 'Invalid role'])->withInput();
            }

            Log::info('Role retrieved', ['role_id' => $role->id]);

            // Create user with role_id reference
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'home_address' => $request->home_address,
                'employee_id' => $request->employee_id,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email_verified_at' => null, // Set to null for verification
                'role_id' => $role->id, // Save the role ID
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            DB::commit();
            Log::info('Transaction committed successfully');

            // Send email verification notification
            if ($user instanceof MustVerifyEmail) {
                Log::info('Sending email verification notification');
                $user->sendEmailVerificationNotification();
            }

            Log::info('Registration successful');
            return redirect()->route('verification.notice')
                             ->with('message', 'Registration successful. Please check your email to verify your account.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
                'employee_id' => $request->employee_id,
                'input' => $request->all(),
            ]);
            return back()->withErrors(['message' => 'Failed to register user. Please try again.'])->withInput();
        }
    }
}
