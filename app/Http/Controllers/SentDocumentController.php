<?php
namespace App\Http\Controllers;
use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use App\Models\Employee;
use App\Models\Document;
use App\Models\RequestDocument;
use App\Models\SetDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;



class SentDocumentController extends Controller
{
    public function index($viewName)
    {
        $userEmployeeId = auth()->user()->employee_id;

        $employee = Employee::where('employee_id', $userEmployeeId)->first();

        $documents = collect(); 

        if ($employee) {
            $employeeId = $employee->id;

        // Fetch Forwarded Documents
        $forwardedDocuments = ForwardedDocument::with(['forwardedToEmployee', 'document'])
            ->where('forwarded_by', $employeeId)
            ->whereNotIn('status', ['archive', 'deleted'])
            ->get();

        // Fetch Sent Documents
        $sentDocuments = SendDocument::with(['sender', 'document'])
            ->where('issued_by', $employeeId)
            ->whereNotIn('status', ['archive', 'deleted'])
            ->get();

        // Combine the two collections
        $documents = $forwardedDocuments->merge($sentDocuments)->map(function ($doc) {
            return [
                'id' => $doc->forwarded_document_id ?? $doc->send_id,
                'type' => isset($doc->forwarded_document_id) ? 'Forwarded' : 'Sent',
                'receiver_name' => optional($doc->forwardedToEmployee ?? $doc->recipient)->first_name . ' ' .
                    optional($doc->forwardedToEmployee ?? $doc->recipient)->last_name,
                'document_name' => $doc->document->document_name ?? $doc->document_subject,
                'message' => $doc->message ?? null,
                'status' => $doc->status,
                'date' => $doc->forwarded_date ?? $doc->issued_date,
                'file_path' => $doc->document->file_path ?? $doc->file_path ?? null,
            ];
        });
        } else {
            \Log::error('Employee record not found for user with employee_id: ' . $userEmployeeId);
        }
        // Return combined documents to the view
        return view($viewName, ['documents' => $documents]);
    }


    public function sentRequested(Request $request){
        try {
            $validatedData = $request->validate([
                'docu-id'=>'required',
                'sender-name' => 'required',
                'request-id' => 'required',
                'document-subject' => 'required|string',
                'document-purpose' => 'required|string',
                'file' => 'required|mimes:pdf|max:5120', // Ensure file is required and only PDF up to 5MB
                'date' => 'required',
            ]);
            $employeeID = Employee::where('employee_id', $validatedData['sender-name'])->first()->id;
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('documents', 'public');
                
                SendDocument::create([
                    'issued_to' => $validatedData['request-id'],
                    'issued_by' => $employeeID,
                    'status' => 'delivered',
                    'document_subject'=>$validatedData['document-subject'],
                    'file_path' => $path
                ]);
                $req = RequestDocument::where('request_id', $validatedData['docu-id'])->first();
                $req->approval_status = 'Approved';
                $req->save();
    
                return response()->json(['msg' => 'Successful']);
            } else {
                return response()->json(['error' => 'File not uploaded'], 400);
            }
        } catch (\Exception $e) {
            // Log the error to Laravel logs
            \Log::error('Error in sentUpload: ' . $e->getMessage());
            return response()->json(['error' => 'Server error occurred'.$e->getMessage()], 500);
        }
  
    }
}