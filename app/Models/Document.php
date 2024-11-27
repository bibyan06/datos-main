<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Event\DocumentParsedEvent;

class Document extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'document_id'; 

    protected $table = 'documents';
    protected $fillable = [
        'document_id',
        'document_number',
        'document_name',
        'description',
        'category_id',
        'file_path',
        'document_status',
        'remark',
        'upload_date',
        'uploaded_by'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tags', 'document_id', 'tag_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'uploaded_by');
    // }

    public function scopePending($query)
    {
        return $query->where('document_status', 'Pending');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by', 'id');
    }

    public function scopeDeclined($query)
    {
        return $query->where('document_status', 'Declined');
    }
    
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }
}
