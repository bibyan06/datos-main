<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileController extends Controller
{
    public function admin_account()
    {
        $authUser = Auth::user();
        $user = User::where('employee_id', $authUser->employee_id)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }
        
        return view('office_staff.admin_account', compact('user'));
    }


    public function os_account()
    {
        $authUser = Auth::user();
        $user = User::where('employee_id', $authUser->employee_id)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }
        
        return view('office_staff.os_account', compact('user'));
    }

    public function dean_account()
    {
        $authUser = Auth::user();
        $user = User::where('employee_id', $authUser->employee_id)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }
        
        return view('dean.dean_account', compact('user'));
    }

    // public function showAdminPfp()
    // {
    //     $user = Auth::user();
    //     $initials = strtoupper(substr($user->first_name, 0, 1)) . strtoupper(substr($user->last_name, 0, 1));

    //     return view('icon.show', compact('initials'));
    // }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'gender' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11',
            'home_address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);

        $user->last_name = $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->age = $request->input('age');
        $user->gender = $request->input('gender');
        $user->phone_number = $request->input('phone_number');
        $user->home_address = $request->input('home_address');
        $user->email = $request->input('email');
        $user->username = $request->input('username');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save(); // This will automatically update the `updated_at` column

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    public function updateDeanProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                \Log::warning('User not authenticated while updating profile.');
                return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
            }
        
            \Log::info('Starting profile update for user ID: ' . $user->user_id);
        
            $validator = $this->validateProfileData($request, $user);
            if ($validator->fails()) {
                \Log::warning('Validation failed for profile update.', $validator->errors()->toArray());
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
        
            DB::beginTransaction();
            $this->updateUserProfile($user, $request);
            DB::commit();
        
            \Log::info('Profile updated successfully for user ID: ' . $user->user_id);
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!', 'user' => $user]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile update error for user ID: ' . $user->user_id . ' - ' . $e->getMessage());
        
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    protected function validateProfileData(Request $request, $user)
{
    $emailUniqueRule = 'unique:users,email,' . $user->user_id . ',user_id';

    // Skip uniqueness validation if the email hasn't changed
    if ($request->input('email') === $user->email) {
        $emailUniqueRule = 'nullable|string|email|max:255';
    }

    // Base validation rules
    $rules = [
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'age' => 'required|integer|min:18',
        'gender' => 'nullable|string|max:255',
        'phone_number' => 'required|string|min:11|max:11',
        'home_address' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|' . $emailUniqueRule,
        'username' => 'required|string|max:255',
    ];

    // Only add password validation rules if password is being changed
    if ($request->filled('password')) {
        $rules['current_password'] = 'required|string|min:8';
        $rules['password'] = [
                'required', 
                'string', 
                'min:8', 
                'regex:/[0-9]/', 
                'confirmed', 
                'different:current_password',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('current_password') && Hash::check($request->current_password, Auth::user()->password) && $value === $request->current_password) {
                        $fail('The new password must be different from the current password.');
                    }
                },
            ];
        } else {
            // Set password fields to nullable if not updating password
            $rules['password'] = 'nullable|string|min:8';
            $rules['current_password'] = 'nullable|string';
        }

        return Validator::make($request->all(), $rules);
    }

    protected function updateUserProfile($user, Request $request)
    {
        $user->last_name = $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->age = $request->input('age');
        $user->gender = $request->input('gender');
        $user->phone_number = $request->input('phone_number');
        $user->home_address = $request->input('home_address');
        $user->email = $request->input('email');
        $user->username = $request->input('username');

        // Check if the current password is correct before updating the password
        if ($request->filled('current_password') && Hash::check($request->current_password, $user->password)) {
            // Hash new password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
        } elseif ($request->filled('current_password')) {
            \Log::warning('Current password does not match for user ID: ' . $user->user_id);
            throw new \Exception('Current password is incorrect.');
        }

        $user->save();
    }

    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();
        $emailUniqueRule = 'unique:users,email,' . $user->user_id . ',user_id';

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'age' => 'required|integer|min:18|max:120',
            'gender' => 'required|string',
            'email' => 'required|string|email|max:255|' . $emailUniqueRule,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->home_address = $request->address;
        $user->save();

        return response()->json(['success' => 'Profile updated successfully']);
    }

    public function updateOfficeStaffProfile(Request $request)
    {
        $user = Auth::user();
        $emailUniqueRule = 'unique:users,email,' . $user->user_id . ',user_id';

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'username'=>'required|string|max:50',
            'age' => 'required|integer|min:18|max:120',
            'gender' => 'required|string',
            'email' => 'required|string|email|max:255|' . $emailUniqueRule,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->username = $request -> username;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->home_address = $request->address;
        $user->save();

        return response()->json(['success' => 'Profile updated successfully']);
    }
}


