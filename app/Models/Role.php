<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false; // If you don't have created_at and updated_at columns

    protected $table = 'roles';

    protected $fillable = [
        'position',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
