<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendDocument extends Model
{
    protected $table = 'send_document';
    protected $primaryKey ='send_id'; 
    protected $fillable = ['send_id', 'issued_to', 'document_id', 'issued_by', 'status','document_subject', 'issued_date','file_path'];

    // Relation to the Employee model (issued to)
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id', 'id');
    // }

    // Relation to the Document model
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }

    // Relation to the Employee model (recipient)
    public function recipient()
    {
        return $this->belongsTo(Employee::class, 'issued_to', 'id');
    }

    // Relation to the Employee model (sender)
    public function sender()
    {
        return $this->belongsTo(Employee::class, 'issued_by', 'id');
    }
}
