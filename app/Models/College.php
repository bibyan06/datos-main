<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    // Specify the table name since it's singular
    protected $table = 'college';

    // Disable automatic timestamps since the table doesn't have `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable properties to allow mass assignment
    protected $fillable = ['college_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'college_id');
    }

        
}
