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

class NotificationController extends Controller{

    public function index($viewName)
{
    $userEmployeeId = auth()->user()->employee_id;

    $employee = Employee::where('employee_id', $userEmployeeId)->first();

    $documents = collect();

    if ($employee) {
        $employeeId = $employee->id;
        $uploadedBy = $employee->first_name . ' ' . $employee->last_name;

        // Fetch Forwarded Documents
        $forwardedDocuments = ForwardedDocument::with(['forwardedTo', 'document', 'forwardedBy'])
            ->where('forwarded_to', $employeeId)
            ->whereNotIn('status', ['archiveNotif', 'deleted'])
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->forwarded_document_id,
                    'type' => 'Forwarded',
                    'receiver_name' => optional($doc->forwardedBy)->first_name . ' ' . optional($doc->forwardedBy)->last_name,
                    'document_name' => optional($doc->document)->document_name,
                    'message' => $doc->message,
                    'status' => $doc->status,
                    'date' => $doc->forwarded_date,
                    'file_path' => $doc->document->file_path ?? null,
                ];
            });

        // Fetch Sent Documents
        $sentDocuments = SendDocument::with(['sender', 'document'])
            ->where('issued_to', $employeeId)
            ->whereNotIn('status', ['archiveNotif', 'deleted'])
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->send_id,
                    'type' => 'Sent',
                    'receiver_name' => optional($doc->sender)->first_name . ' ' . optional($doc->sender)->last_name,
                    'document_name' => $doc->document_subject,
                    'message' => null,
                    'status' => $doc->status,
                    'date' => $doc->issued_date,
                    'file_path' => $doc->file_path ?? null,
                ];
            });

        // Fetch Declined Documents
        $declinedDocuments = Document::where('uploaded_by', $uploadedBy)
            ->where('document_status', 'Declined')
            ->whereNotIn('status', ['archiveNotif', 'deleted'])
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->document_id,
                    'type' => 'Declined',
                    'receiver_name' => $doc->declined_by,
                    'document_name' => $doc->document_name,
                    'message' => $doc->remark,
                    'status' => $doc->document_status,
                    'date' => $doc->declined_date,
                    'file_path' => $doc->file_path ?? null,
                ];
            });

        // Ensure all collections remain collections
        $forwardedDocuments = collect($forwardedDocuments);
        $sentDocuments = collect($sentDocuments);
        $declinedDocuments = collect($declinedDocuments);

        // Combine all documents
        if ($declinedDocuments->isNotEmpty() || $forwardedDocuments->isNotEmpty() || $sentDocuments->isNotEmpty()) {
            $documents = $forwardedDocuments->merge($sentDocuments)->merge($declinedDocuments);
        }
    } else {
        \Log::error('Employee record not found for user with employee_id: ' . $userEmployeeId);
    }

    // Return combined documents to the view
    return view($viewName, ['documents' => $documents]);
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
    public function newDestroy($id,$status,$type){
        if($type=='Sent'){
            $sent = SendDocument::where('send_id', $id)->first();
            if ($sent) {
                $sent->status = $status;
                $sent->update();
                return response()->json([
                    'success' => true,
                    'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
                ]);
            }
        }elseif ($type== 'Forwarded'){
            $forwardedDocuments = ForwardedDocument::where('forwarded_document_id', $id)->first();
            if ($forwardedDocuments) {
                $forwardedDocuments->status = $status;
                $forwardedDocuments->update();
                return response()->json([
                    'success' => true,
                    'message' => 'Document ' . ($status=='viewed'?'Restored':$status) . ' successfully.',
                ]);
            }
        }else {
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
    public function Newdestroydeclined($id, $status, $type)
    {
        if($type=='Declined'){
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
