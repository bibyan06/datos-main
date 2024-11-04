<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\TemporaryUser;
use Illuminate\Auth\Events\Verified;

class MoveTemporaryUserData
{
    public function handle(Verified $event)
    {
        // Assuming you have a relationship between TemporaryUser and User models
        $temporaryUser = TemporaryUser::where('email', $event->user->email)->first();

        if ($temporaryUser) {
            // Move data to users table
            User::create([
                'employee_id' => $temporaryUser->employee_id,
                'last_name' => $temporaryUser->last_name,
                'first_name' => $temporaryUser->first_name,
                'middle_name' => $temporaryUser->middle_name,
                'age' => $temporaryUser->age,
                'gender' => $temporaryUser->gender,
                'phone_number' => $temporaryUser->phone_number,
                'home_address' => $temporaryUser->home_address,
                'email' => $temporaryUser->email,
                'username' => $temporaryUser->username,
                'password' => $temporaryUser->password,
            ]);

            // Optionally, delete the temporary user record
            $temporaryUser->delete();
        }
    }
}
