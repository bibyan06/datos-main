<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForwardedDocument extends Model
{
    public $timestamps = false;
    protected $table = 'forwarded_documents';
    protected $primaryKey = 'forwarded_document_id';
    protected $fillable = ['forwarded_document_id', 'document_id', 'forwarded_by', 'forwarded_to', 'forwarded_date', 'status', 'message'];

    // Relation to the Employee model (forwarded to)
    public function forwardedToEmployee()
    {
        return $this->belongsTo(Employee::class, 'forwarded_to', 'id');
    }

    // Relation to the Employee model (forwarded by)
    public function forwardedByEmployee()
    {
        return $this->belongsTo(Employee::class, 'forwarded_by', 'id');
    }

    // Relation to the Document model
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }


    public function forwardedTo()
    {
        return $this->belongsTo(Employee::class, 'forwarded_to');
    }

    // Relation to the Employee model (forwarded by)
    public function forwardedBy()
    {
        return $this->belongsTo(Employee::class, 'forwarded_by');
    }

    // Relation to the Document model
    public function documents()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
