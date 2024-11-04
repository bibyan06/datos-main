<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDocument extends Model
{
    
    protected $table = 'request_document';
    protected $primaryKey = 'request_id';
    protected $fillable = ['request_id',	'document_id',	'requested_by',	'college_id'	,'document_subject'	,'request_purpose'	,'request_date','forwarded_at','approval_status'];

    public function requestedBy()
    {
        return $this->belongsTo(Employee::class, 'requested_by');
    }
}
