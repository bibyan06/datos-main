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
        return $this->belongsTo(Employee::class, 'requested_by', 'id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }

    public function scopeFilterStatus($query, $status)
    {
        return $status ? $query->whereIn('status', $status) : $query;
    }

    public function scopeFilterApprovalStatus($query, $approvalStatus)
    {
        return $query->whereIn('approval_status', $approvalStatus);
    }

}
