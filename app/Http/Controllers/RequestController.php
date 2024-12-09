<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\RequestDocument;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $req){
        $validated = $req->validate([
            'document-subject'=>'required|string',
            'request-purpose'=>'required|string',
            'colleges'=>'required|string',
         
        ]);
        $id = auth()->user()->employee_id;

        $empID =  Employee::where('employee_id',$id)->first()->id;

        $requestedDocuments = RequestDocument::create([
            'requested_by'=>$empID,
            'college_id'=>$validated['colleges'],
            'document_subject'=>$validated['document-subject'],
            'request_purpose'=>$validated['request-purpose'],
            'approval_status'   => 'Pending',
        ]);
        return redirect()->route('dean.documents.dean_request')->with('success', 'Request Document has been submitted successfully.');
  

    }
}
