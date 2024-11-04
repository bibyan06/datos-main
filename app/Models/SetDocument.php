<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetDocument extends Model
{
    protected $table = "send_document";
    protected $primaryKey = 'send_id';
    protected $fillable = [
        'issued_to',
        'issued_by',
        'status',
        'file_path'
    ];
}
