<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|max:20|unique:users',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'age' => 'required|numeric|integer|min:18|max:120',
            'gender' => 'required|string|in:male,female',
            'phone_number' => 'required|string|max:20',
            'home_address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // Determine user_type based on employee_id
            $user_type = $this->determineUserType($request->employee_id);

            // Log user type determination
            Log::info('Determined user type: ' . $user_type);

            // Create user
            $user = User::create([
                'employee_id' => $request->employee_id,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'home_address' => $request->home_address,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Set email_verified_at if you want it to be verified immediately
                'user_type' => $user_type,
            ]);

            // Log user creation data
            Log::info('User created: ', $user->toArray());

            // Create employee record with the same position
            Employee::create([
                'employee_id' => $request->employee_id,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'position' => $user_type, // Position is the same as user_type in this case
            ]);

            // Log employee creation
            Log::info('Employee created with position: ' . $user_type);

            DB::commit();

            return redirect('/login')->with('message', 'Registration successful. You can now log in.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User registration failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to register user'], 500);
        }
    }

    private function determineUserType($employee_id)
    {
        if (strpos($employee_id, '01') === 0) {
            return 'admin';
        } elseif (strpos($employee_id, '02') === 0) {
            return 'office_staff';
        } elseif (strpos($employee_id, '03') === 0) {
            return 'dean';
        }
        return 'employee'; // Default type
    }
}
