<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    // Specify the table name
    protected $table = 'login_session';

    // Specify the primary key
    protected $primaryKey = 'session_id';

    // If the primary key is not an auto-incrementing integer
    public $incrementing = false;

    // If the primary key is not an integer
    protected $keyType = 'string';
}
