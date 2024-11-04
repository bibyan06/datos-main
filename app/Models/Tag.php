<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'tag_id';
    protected $fillable = ['tag_name'];

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_tags', 'tag_id', 'document_id');
    }
}
