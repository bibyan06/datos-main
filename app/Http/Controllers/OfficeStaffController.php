<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Employee;
use App\Models\ForwardedDocument;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OfficeStaffController extends Controller
{
    public function dashboard()
    {
        return view('office_staff.os_dashboard');
    }
    public function os_account()
    {
        return view('office_staff.os_account');
    }

    public function os_upload_document()
    {
        return view('office_staff.os_upload_document');
    }

    public function memorandum()
    {
        return view('office_staff.documents.memorandum');
    }

    // public function search()
    // {
    //     return view('office_staff.documents.os_search');
    // }


    public function os_view_docs()
    {
        return view('office_staff.documents.os_view_docs');
    }
    public function edit_docs()
    {
        return view('office_staff.documents.edit_docs');
    }

    public function os_notification()
    {
        return view('office_staff.os_notification');
    }

    public function someMethod()
    {
    $user = auth()->user();

    if (strpos($user->employee_id, '02') !== 0) {
        abort(403, 'Unauthorized action.');
    }
    // Proceed with the action
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
    
        // Return the search results
        return view('office_staff.documents.os_search', compact('documents'));
    }
    

    public function showApprovedDocuments()
    {
        // Fetch all approved documents
       $documents = Document::where('document_status', 'Approved')->where('status',NULL)->get();
        return view('office_staff.documents.os_search', compact('documents'));
    }

    public function showAllDocs(Request $request)
    {
        // Debugging: Log the received category
        \Log::info('Category: ' . $request->category);

        $query = Document::query();

        if ($request->has('category') && !empty($request->category)) {
            // Debugging: Log the query
            \Log::info('Filtering by category: ' . $request->category);
            $query->where('category_name', $request->category);
        }

        $documents = $query->where('document_status', 'Approved')->get();

        return view('office_staff.documents.os_search', compact('documents'));
    }


    public function showHomePage()
    {
        $employeeId = Auth::user()->employee->id;
        $documents = Document::where('document_status', '=', 'Approved')
            ->where(function ($query) {$query->whereNull('status')
            ->orWhere('status', '!=', 'archive');})
            ->get();
        
        $forwarded = ForwardedDocument::where('forwarded_to', $employeeId)
            ->orderBy('forwarded_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->type = 'Forwarded';
                $item->date = $item->forwarded_date;
                return $item;
            });

        $declined = Document::where('uploaded_by', (string)$employeeId)  
            ->orderBy('declined_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->type = 'Declined Document';
                $item->date = $item->declined_date;
                return $item;
            });
    
        return view('home.office_staff', compact('documents','forwarded', 'declined'));
    }

    public function view($document_id)
    {
        $document = Document::findOrFail($document_id);

        if (!$document) {
            abort(404, 'Document not found.');
        }
    
        return view('office_staff.documents.os_view_docs', compact('document'));
    }

    public function edit($document_id)
    {
        $document = Document::find($document_id);
        return view('office_staff.documents.edit_docs', compact('document'));
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

        return redirect()->route('office_staff.documents.os_view_docs', $document_id)
                         ->with('success', 'Document updated successfully.');
    }


    public function category_count()
    {
        // Retrieve documents uploaded by the current user
        $currentUserName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $documents = Document::where('uploaded_by', $currentUserName)->get();
    
        // Count documents per category
        $memorandumCount = Document::where('uploaded_by', $currentUserName)->where('category_name', 'Memorandum')->where('document_status','Approved')->count();
        $claimMonitoringSheetCount = Document::where('uploaded_by', $currentUserName)->where('category_name', 'Claim Monitoring Sheet')->where('document_status','Approved')->count();
        $mrspCount = Document::where('uploaded_by', $currentUserName)->where('category_name', 'Monthly Report Service of Personnel')->where('document_status','Approved')->count();
        $auditedDVCount = Document::where('uploaded_by', $currentUserName)->where('category_name', 'Audited Disbursement Voucher')->where('document_status','Approved')->count();
       
        // Pass the data to the view
        return view('office_staff.os_dashboard', compact('documents', 'memorandumCount', 'claimMonitoringSheetCount', 'mrspCount', 'auditedDVCount'));
    }

    public function display_uploaded_docs()
    {
        $currentUserName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $documents = Document::where('uploaded_by', $currentUserName)->get();

        // Count documents per category
        $memorandumCount = Document::where('category_name', 'Memorandum')->count();
        $claimMonitoringSheetCount = Document::where('category_name', 'Claim Monitoring Sheet')->count();
        $mrspCount = Document::where('category_name', 'Monthly Report Service of Personnel')->count();
        $auditedDVCount = Document::where('category_name', 'Audited Disbursement Voucher')->count();

        return view('office_staff.os_dashboard', compact('documents', 'memorandumCount', 'claimMonitoringSheetCount', 'mrspCount', 'auditedDVCount'));
    }
    


    public function showMemorandums()
    {
        
        $documents = Document::where('category_name', 'Memorandum')
                            ->where('document_status', 'Approved') // Show only approved documents
                            ->get();
        
        return view('office_staff.documents.memorandum', compact('documents'));
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
        $documents = Document::where('category_name', 'Monthly Report Service Personnel')
                            ->where('document_status', 'Approved') // Show only approved documents
                            ->get();

        return view('office_staff.documents.mrsp', compact('documents'));
    }

    public function showCms()
    {
        // Assuming category_id for Memorandum is '1' or replace it with the correct value
        $documents = Document::where('category_name', 'Claim Monitoring Sheet')
                            ->where('document_status', 'Approved') // Show only approved documents
                            ->get();

        return view('office_staff.documents.cms', compact('documents'));
    }

    public function showAuditedDV()
    {
        // Assuming category_id for Memorandum is '1' or replace it with the correct value
        $documents = Document::where('category_name', 'Audited Disbursement Voucher')
                            ->where('document_status', 'Approved') // Show only approved documents
                            ->get();

        return view('office_staff.documents.audited_dv', compact('documents'));
    }

    public function showProfile()
    {
        // Eager load the user's role
        $user = User::with('role')->find(Auth::id());

        return view('office_staff._account', compact('user'));
    }

    //Forward Docs

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
        
        return response()->json(['message' => 'Document forwarded successfully!']);
    }

    
    public function archiveDocs(){
       
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $currentUserName = auth()->user()->first_name . ' ' . auth()->user()->last_name;

        // Fetch forwarded documents marked as archive
        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])
            ->where('forwarded_to', $id)
            ->where('status', 'archiveNotif')
            ->get();
    
        // Fetch declined documents marked as archive, but only for those uploaded by the current user
        $uploaded = Document::where('document_status', 'Declined')
            ->where('status', 'archiveNotif')
            ->where('uploaded_by', $currentUserName)  
            ->get();
        
        
        return view('office_staff.os_archive',compact('forward', 'uploaded'));
    }
    
    public function trash()
    {
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $currentUser = auth()->user()->first_name . ' ' . auth()->user()->last_name;

        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])
            ->where('forwarded_to', $id)
            ->where('status', 'deleted')
            ->get();

        $forwardedDocuments = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])
            ->where('forwarded_by', $id)
            ->where('status', 'deleted')
            ->get();

        $uploaded = Document::where('document_status', 'Declined')
        ->where('status', 'deleted')
        ->where('uploaded_by', $currentUser)
        ->get();

        return view('office_staff.os_trash',compact('forward', 'uploaded','forwardedDocuments'));
    }

}
