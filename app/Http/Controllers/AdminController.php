<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\SendDocument;
use App\Models\ForwardedDocument;
use App\Models\RequestDocument;
use App\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        $documents = Document::all(); 
        $totalDocuments = $documents->count();
        $totalEmployees = Employee::count(); 
        
        return view('admin.admin_dashboard', compact('documents', 'totalDocuments', 'totalEmployees'));;
    }

    public function admin_account()
    {
        return view('admin.admin_account');
    }

    public function admin_upload_document()
    {
        return view('admin.admin_upload_document');
    }

    public function view_document()
    {
        return view('admin.admin_view_document');
    }

    public function college_dean()
    {
        return view('admin.college_dean');
    }

    public function office_staff()
    {
        return view('admin.office_staff');
    }

    public function notification()
    {
        return view('admin.admin_notification');
    }

    public function review_docs()
    {
        // Fetch documents with 'pending' status
        $documents = Document::where('document_status', 'Pending')->get();
        return view('admin.documents.review_docs', compact('documents'));
    }

    public function approved_docs()
    {
        $documents = Document::where('document_status', 'Approved')->get();
        return view('admin.documents.approved_docs', compact('documents'));
    }

    public function declined_docs()
    {
        $documents = Document::where('document_status', 'Declined')->get();
        return view('admin.documents.declined_docs', compact('documents'));
    }

    public function edit_docs()
    {
        return view('admin.documents.edit_docs');
    }

    public function memorandum()
    {
        return view('admin.documents.memorandum');
    }

    public function admin_order()
    {
        return view('admin.documents.admin_order');
    }

    public function mrsp()
    {
        return view('admin.documents.mrsp');
    }

    public function cms()
    {
        return view('admin.documents.cms');
    }

    public function audited_dv()
    {
        return view('admin.documents.audited_dv');
    }

    public function request_docs()
    {
        $documents  = RequestDocument::with(['requestedBy'])->get();
        return view('admin.documents.request_docs', compact('documents'));
    }

    public function declineRequest(Request $request)
    {
        $document = RequestDocument::find($request->request_id);
        if ($document) {
            $document->approval_status = 'declined';
            $document->declined_date = now();
            $document->remarks= $request->remarks;
            $document->save();
    
            return response()->json(['success' => true, 'message' => 'Document declined successfully.']);
        }
        
        return response()->json(['success' => false, 'message' => 'Document not found.'], 404);
    }


    public function sent_docs()
    {
        return view('admin.documents.sent_docs');
    }

    public function view_docs()
    {
        return view('admin.documents.view_docs');
    }
    public function all_docs()
    {
        return view('admin.documents.all_docs');
    }

    public function someMethod()
    {
        $user = auth()->user();

        // Ensure the user has the '01' prefix in employee_id for admin actions
        if (strpos($user->employee_id, '01') !== 0) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function searchDocuments(Request $request)
    {
        $query = $request->input('query');
    
        $documents = Document::where('document_status', 'Approved')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($statusQuery) {
                    $statusQuery->whereNull('status')
                                ->orWhere('status', '!=', 'archive');
                })
                ->where(function ($searchQuery) use ($query) {
                    $searchQuery->where('document_name', 'LIKE', "%{$query}%")
                                ->orWhereHas('tags', function ($tagQuery) use ($query) {
                                    $tagQuery->where('tag_name', 'LIKE', "%{$query}%");
                                });
                });
            })
            ->with(['tags' => function ($tagSelectQuery) {
                $tagSelectQuery->select('tags.tag_id as tag_id', 'tag_name');
            }])
            ->get();
    
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            // Return the documents as JSON for AJAX requests
            return response()->json($documents);
        }
    
        // Return the view for non-AJAX requests
        return view('admin.admin_search', compact('documents'));
    }

    public function reviewDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $action = $request->input('action');

        if ($action == 'Approve') {
            $document->document_status = 'Approved';
        } elseif ($action == 'Decline') {
            $document->document_status = 'Declined';
        }
          
            $document->save();

        if ($document->document_status == 'Approved') {
            return redirect()->route('admin.documents.approved_docs')->with('success', 'Document approved successfully.');
        } else {
            return redirect()->route('admin.documents.review_docs')->with('success', 'Document declined successfully.');
        }
    }

    // public function showPendings()
    // {
    //     $documentPendingCount = Document::where('document_status', 'Pending')->count();
    //     $requestPendingCount = RequestDocument::where('approval_status', 'pending')->count();
    //     $forwardPendingCount = DB::table('forwarded_documents')->where('status', 'delivered')->count();
    //     $sendPendingCount = DB::table('send_document')->where('status', 'delivered')->count();
        
    //     $pendingCount = $documentPendingCount + $requestPendingCount;
    //     $notificationCount = $forwardPendingCount + $sendPendingCount;
    
    //     return view('layouts.admin_layout', compact('pendingCount', 'notificationCount')); 
    // }

    public function adminHome()
    {
       
        $documents = Document::where('document_status', '=', 'Approved')
                             ->where(function ($query) {
                                 $query->whereNull('status')
                                       ->orWhere('status', '!=', 'archive');
                             })
                             ->get();
    
        return view('home.admin', compact('documents'));
    }

    public function adminDashboard()
    {
     
        $totalDocuments = Document::where('document_status', '=', 'Approved')->count();
        $totalEmployees = Employee::count();

        \Log::info('Total Approved Documents: ' . $totalDocuments);
        return view('admin.admin_dashboard', compact('totalDocuments', 'totalEmployees'));
    }

    public function view($document_id)
    {
        $document = Document::findOrFail($document_id);

        if (!$document) {
            abort(404, 'Document not found.');
        }

        return view('admin.documents.view_docs', compact('document'));
    }

    public function edit($document_id)
    {
        $document = Document::find($document_id);
        return view('admin.documents.edit_docs', compact('document'));
    }

    public function update(Request $request, $document_id)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $document = Document::findOrFail($document_id);
        $document->document_name = $request->input('document_name');
        $document->description = $request->input('description');
        $document->save();

        return redirect()->route('admin.documents.view_docs', $document_id)
            ->with('success', 'Document updated successfully.');
    }


    public function category_count()
    {
        
        $totalDocuments = Document::count();
        $totalEmployees = User::count();

        
        $claimMonitoringSheetCount = Document::where('category_name', 'Claim Monitoring Sheet')->where('document_status', 'Approved')->count();
        $memorandumCount = Document::where('category_name', 'Memorandum')->where('document_status', 'Approved')->count();
        $mrspCount = Document::where('category_name', 'Monthly Report Service of Personnel')->where('document_status', 'Approved')->count();
        $auditedDVCount = Document::where('category_name', 'Audited Disbursement Voucher')->where('document_status', 'Approved')->count();

        return view('admin.admin_dashboard', compact(
            'totalDocuments',
            'totalEmployees',
            'claimMonitoringSheetCount',
            'memorandumCount',
            'mrspCount',
            'auditedDVCount'
        ));
    }

    public function display_uploaded_docs()
    {
        $documents = Document::orderByRaw('CAST(document_number AS UNSIGNED) ASC')->get();

        $totalDocuments = Document::where('document_status','Approved')->count();
        $totalEmployees = Employee::count();

        $claimMonitoringSheetCount = Document::where('category_name', 'claim_monitoring_sheet')->count();
        $memorandumCount = Document::where('category_name', 'memorandum')->count();
        $mrspCount = Document::where('category_name', 'mrsp')->count();
        $auditedDVCount = Document::where('category_name', 'audited_DV')->count();

        return view('admin.admin_dashboard', compact(
            'documents',
            'totalDocuments',
            'totalEmployees',
            'claimMonitoringSheetCount',
            'memorandumCount',
            'mrspCount',
            'auditedDVCount'
        ));
    }

    public function showProfile()
    {
        // Eager load the role with the user
        $user = User::with('role')->find(Auth::id());

        return view('admin.admin_account', compact('user'));
    }

    public function showOfficeStaff()
    {
        // Retrieve all office staff where the second segment of employee_id is '002'
        $officeStaff = Employee::whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(employee_id, '-', 2), '-', -1) = '002'")
            ->get();

        return view('admin.office_staff', ['officeStaff' => $officeStaff]);
    }

    public function showMemorandums()
    {
        $documents = Document::where('category_name', 'Memorandum')
            ->where('document_status', 'Approved') 
            ->get();

        return view('admin.documents.memorandum', compact('documents'));
    }

    // public function showAdminOrders()
    // {
    //     // Assuming category_id for Memorandum is '1' or replace it with the correct value
    //     $documents = Document::where('category_name', 'Administrative Orders')
    //                         ->where('document_status', 'approved') // Show only approved documents
    //                         ->get();

    //     return view('admin.documents.admin_order', compact('documents'));
    // }

    public function showMrsp()
    {
        // Assuming category_id for Memorandum is '1' or replace it with the correct value
        $documents = Document::where('category_name', 'Monthly Report Service of Personnel')
            ->where('document_status', 'Approved')
            ->get();

        return view('admin.documents.mrsp', compact('documents'));
    }

    public function showCms()
    {
        // Assuming category_id for Memorandum is '1' or replace it with the correct value
        $documents = Document::where('category_name', 'Claim Monitoring Sheet')
            ->where('document_status', 'Approved') // Show only approved documents
            ->get();

        return view('admin.documents.cms', compact('documents'));
    }

    public function showAuditedDV()
    {
        // Assuming category_id for Memorandum is '1' or replace it with the correct value
        $documents = Document::where('category_name', 'Audited Disbursement Voucher')
            ->where('document_status', 'Approved') // Show only approved documents
            ->get();

        return view('admin.documents.audited_dv', compact('documents'));
    }

    //For forwarded docs

    public function getEmployee()
    {
        $currentUserId = Auth::user()->employee_id;  // Get the employee_id of the current user
        $employees = Employee::where('employee_id', '!=', $currentUserId)->get();  // Fetch employees except current user

        // Log employees to check the output
        logger()->info($employees);

        return response()->json($employees);  // Return employees as JSON
    }

    public function forwardDocument(Request $request)
    {
        // Get the document and employee IDs from the request
        $documentId = $request->input('document_id');
        $employeeId = $request->input('employee_id');

        // Your logic for forwarding the document (e.g., saving the forwarding record to the database)
        // Example: DocumentForwarding::create([...]);

        return response()->json(['message' => 'Document forwarded successfully!']);
    }

    public function archiveNotif()
    {
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $currentUser = auth()->user()->first_name . ' ' . auth()->user()->last_name;

        // Fetch forwarded documents marked as archive
        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])
            ->where('forwarded_to', $id)
            ->where('status', 'archive')
            ->get();

        // Fetch declined documents marked as archive, but only for those uploaded by the current user
        $uploaded = Document::where('document_status', 'Declined')
            ->where('status', 'archive')
            ->where('uploaded_by', $currentUser)  
            ->get();

        return view('admin.archive_notif', compact('forward', 'uploaded'));
    }



    public function archiveDocs()
    {
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first();
        $forward = Document::with(['user', 'tags'])
            ->where('status', 'archive')
            ->orderBy('updated_at', 'ASC')
            ->get();

        return view('admin.archive_docs', compact('forward'));
    }
    
    public function archiveDeclinedDocument($id)
    {
        $document = Document::where('document_id', $id)->first();

        $document = Document::find($id);
        if ($document) {
            $document->document_status = "archive";
            $document->updated_at = now();
            $document->update();

            return response()->json(['success' => true, 'message' => 'Document archived successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Document not found.']);
    }
        
    public function archiveDocument($id)
    {
        $docs = Document::where('document_id', $id)->first();

        if ($docs) {
            $docs->status = "archive";
            $docs->updated_at = now();
            $docs->update();

            return response()->json([
                'success' => true,
                'message' => 'Document archived successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Document not found.',
        ], 404);
    }

    public function trash()
    {
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $currentUser = auth()->user()->first_name . ' ' . auth()->user()->last_name;

        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])
            ->where('forwarded_to', $id)
            ->where('status', 'deleted')
            ->get();

        $sent = SendDocument::with(['recipient', 'document', 'sender'])
            ->where('issued_by', $id)
            ->where('status', 'deleted')
            ->get();

        $uploaded = Document::where('document_status', 'Declined')
            ->where('status', 'deleted')
            ->where('uploaded_by', $currentUser)
            ->get();
            
        return view('admin.trash', compact('forward', 'sent', 'uploaded'));
    
    }
    public function restoreDocs($id)
    {
        $docs = Document::where('document_id', $id)->first();
        if ($docs) {
            $docs->status = NULL;
            $docs->updated_at = now();
            $docs->update();
            return response()->json([
                'success' => true,
                'message' => 'Document restored successfully.',
            ]);
        }
    }
}
