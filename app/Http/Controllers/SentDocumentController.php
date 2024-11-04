<?php
namespace App\Http\Controllers;
use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use App\Models\Employee; // Import Employee model
use App\Models\RequestDocument;
use App\Models\SetDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SentDocumentController extends Controller
{
    public function index($viewName)
    {
        // Fetch the authenticated user's employee_id
        $userEmployeeId = auth()->user()->employee_id;
        // Fetch the corresponding employee record from the Employee table
        $employee = Employee::where('employee_id', $userEmployeeId)->first();
        // Ensure that the employee record exists before querying documents
        if ($employee) {
            // Use the employee's id for filtering the forwarded and sent documents
            $employeeId = $employee->id;
            // Fetch forwarded documents where the current user is the one who forwarded the document
            $forwardedDocuments = ForwardedDocument::with(['forwardedToEmployee', 'document'])
                ->where('forwarded_by', $employeeId) // Correct filter for employee id
                ->whereIn('status', ['seen', 'delivered'])
                ->get();
            
            // Fetch sent documents where the current user issued the document
            $sentDocuments = SendDocument::with(['sender', 'document'])
                ->where('issued_by', $employeeId) // Correct filter for employee id
                ->whereIn('status', ['seen', 'delivered'])
                ->get();
            // Log the results to verify data
            \Log::info('Forwarded Documents:', $forwardedDocuments->toArray());
            \Log::info('Sent Documents:', $sentDocuments->toArray());
            // Return the view with the filtered documents
            return view($viewName, compact('forwardedDocuments', 'sentDocuments'));
        } else {
            // Handle case where the employee record doesn't exist for the user
            \Log::error('Employee record not found for user with employee_id: ' . $userEmployeeId);
            return view($viewName)->withErrors(['Employee record not found.']);
        }
    }
    public function sentUpload(Request $request){
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
                $req->approval_status = 'approved';
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