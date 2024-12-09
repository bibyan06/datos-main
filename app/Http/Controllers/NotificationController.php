<?php

namespace App\Http\Controllers;

use App\Models\RequestDocument;
use Illuminate\Http\Request;
use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use App\Models\Employee;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index($viewName)
    {
        // Fetch the authenticated user's employee_id
        $userEmployeeId =  Auth::user()->employee_id;
    
        // Fetch the corresponding employee record from the Employee table
        $employee = Employee::where('employee_id', $userEmployeeId)->first();
    
        if ($employee) {
            $employeeId = $employee->employee_id; 
            $uploadedBy = $employee->first_name . ' ' . $employee->last_name;
    
            // Fetch forwarded documents for the current user
            $forwardedDocuments = ForwardedDocument::with(['forwardedByEmployee', 'document'])
                ->where('forwarded_to', $employee->id)
                ->whereIn('status', ['viewed', 'delivered'])
                ->get();
    
            // Fetch sent documents for the current user
            $sentDocuments = SendDocument::with(['sender', 'document'])
                ->where('issued_to', $employee->id)
                ->whereIn('status', ['viewed', 'delivered'])
                ->get();
    
            // Fetch declined documents uploaded by the current user
            $declinedDocuments = Document::where('uploaded_by', $uploadedBy)
                ->where('document_status', 'Declined')
                ->whereIn('status',['viewed', 'delivered'])
                ->get();

            $requestedDocuments = RequestDocument::with(['requestedBy', 'document'])
                ->where('requested_by', $employee->id)
                ->whereIn('approval_status', ['Declined'])
                ->whereIn('status', ['viewed', 'delivered'])
                ->get();

            // Return the appropriate view with the documents
            return view($viewName, compact('forwardedDocuments', 'sentDocuments', 'declinedDocuments', 'requestedDocuments', ));
        } else {
            \Log::error('Employee record not found for user with employee_id: ' . $userEmployeeId);
            return view($viewName)->withErrors(['Employee record not found.']);
        }
    }
    

    public function getNotificationCount()
    {
        try {
            $user = Auth::user();  // Get the authenticated user object

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $userId = $user->employee_id;  // Get the custom user identifier
            $employee = Employee::where('employee_id', $userId)->first();
            $fullName = $employee->first_name . ' ' . $employee->last_name;

            // Count notifications for the current user in both tables
            $forwardedCount = DB::table('forwarded_documents')
                ->where('status', 'delivered')
                ->where('forwarded_to', $employee->id)
                ->count();

            $sentCount = DB::table('send_document')
                ->where('status', 'delivered')
                ->where('issued_to', $employee->id)
                ->count();
            
            $declinedCount = DB::table('documents')
                ->where('status', 'delivered')
                ->where('uploaded_by', $fullName) 
                ->count();        
    
            $notificationCount = $forwardedCount + $sentCount + $declinedCount;
    
            return response()->json(['notificationCount' => $notificationCount]);
        } catch (\Exception $e) {
            \Log::error("Error in getNotificationCount: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function getDeclinedRequestedCount()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $employee = Employee::where('employee_id', $user->employee_id)->first();
            $fullName = $employee->first_name . ' ' . $employee->last_name;

            // Count declined notifications requested by the user
            $declinedCount = DB::table('request_document')
                ->where('status', 'delivered') 
                ->where('approval_status', 'Declined')
                ->where('requested_by', $employee->id)
                ->count();

            return response()->json(['notificationCount' => $declinedCount]);
        } catch (\Exception $e) {
            \Log::error("Error in getDeclinedRequestedCount: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }



    public function destroy($id, $status)
    {
        $forwardedDocuments = ForwardedDocument::where('forwarded_document_id', $id)->first();
        if ($forwardedDocuments) {
            $forwardedDocuments->status = $status;
            $forwardedDocuments->update();
            return response()->json([
                'success' => true,
                'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
            ]);
        }
    }

    public function destroysent($id, $status)
    {

        $forwardedDocuments = SendDocument::where('send_id', $id)->first();
        if ($forwardedDocuments) {
            $forwardedDocuments->status = $status;
            $forwardedDocuments->update();
            return response()->json([
                'success' => true,
                'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
            ]);
        }
    }

    public function destroydeclined($id, $status)
    {

        $uploadedDocuments = Document::where('document_id', $id)->first();
        if ($uploadedDocuments) {
            $uploadedDocuments->status = $status;
            $uploadedDocuments->update();
            return response()->json([
                'success' => true,
                'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
            ]);
        }
    }

    public function destroyreqdeclined($id, $status)
    {

        $requestedDocuments = RequestDocument::where('request_id', $id)->first();
        if ($requestedDocuments) {
            $requestedDocuments->status = $status;
            $requestedDocuments->update();
            return response()->json([
                'success' => true,
                'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
            ]);
        }
    }
}
