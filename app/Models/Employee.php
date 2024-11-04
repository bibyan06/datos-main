<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $timestamps = false; 

    protected $fillable = [
        'id',
        'employee_id',
        'last_name',
        'first_name',
        'position',
    ];

    // Specify the correct table name
    protected $table = 'employee';

    public function forwardedDocuments()
    {
        return $this->hasMany(ForwardedDocument::class, 'forwarded_to', 'id');
    }
}
