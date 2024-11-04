<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'upload_date',
        'uploaded_by'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'document_tags', 'document_id', 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopePending($query)
    {
        return $query->where('document_status', 'Pending');
    }
}
