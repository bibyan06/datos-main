<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TemporaryUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'last_name',
        'first_name',
        'middle_name',
        'age',
        'gender',
        'phone_number',
        'home_address',
        'email',
        'username',
        'password',
        'verification_token', // assuming you have a token for verification
    ];

    protected $hidden = [
        'password',
    ];
}
