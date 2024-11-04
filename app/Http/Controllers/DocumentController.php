<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{

    public function create()
    {
        $categories = DB::table('category')->get(); // Fetch all categories from the 'category' table
        $documentsCount = Document::count() + 1;
        return view('office_staff.os_upload_document', compact('categories', 'documentsCount'));
    }

    public function create_admin()
    {
        $categories = DB::table('category')->get(); // Fetch all categories from the 'category' table
        $documentsCount = Document::count() + 1;
        return view('admin.admin_upload_document', compact('categories', 'documentsCount'));
    }

    public function store(Request $request)
    {
        Log::info('Upload Document Method Called');

        $allowedCategories = [
            'Memorandum',
            'Audited Disbursement Voucher',
            'Claim Monitoring Sheet',
            'Monthly Report Service of Personnel'
        ];

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'document_number' => 'required|string',
                'document_name' => 'required|string',
                'description' => 'nullable|string',
                'category_name' => 'required|exists:category,category_name',
                'file' => 'required|mimes:pdf|max:5120',
                'tags' => 'nullable|string',
            ]);

            Log::info('Validation passed:', $validatedData);

            // Check if the file is uploaded
            if ($request->hasFile('file')) {
                 $path = $request->file('file')->store('documents', 'public');
                Log::info('File uploaded to: ' . $path);

                $document = new Document();
                $document->document_number = $validatedData['document_number'];
                $document->document_name = $validatedData['document_name'];
                $document->description = $validatedData['description'] ?? null;
                $document->category_name = $validatedData['category_name'];
                $document->file_path = $path;
                $document->document_status = 'Pending';
                $document->upload_date = now();

                // Set the uploaded_by field
                $authenticatedUser = Auth::user();
                $document->uploaded_by = $authenticatedUser ? $authenticatedUser->first_name . ' ' . $authenticatedUser->last_name : 'Unknown User';

                DB::transaction(function () use ($document, $request) {
                    $document->save();

                    // Handle tags if provided
                    if ($request->has('tags')) {
                        $tags = explode(',', $request->input('tags'));
                        foreach ($tags as $tag) {
                            $tagModel = Tag::firstOrCreate(['tag_name' => trim($tag)]);
                            $document->tags()->attach($tagModel->tag_id);
                        }
                    }
                });

                Log::info('Document saved successfully with ID: ' . $document->id);

                return response()->json([
                    'message' => 'Document uploaded and pending review.'
                ], 200);
            } else {
                Log::warning('No file was uploaded with the request.');
                return response()->json(['error' => 'Please upload a file.'], 422);
            }
        } catch (ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error saving document: ' . $e->getMessage());
            return response()->json(['error' => 'Document upload failed.'], 500);
        }
    }


    public function approve($document_id)
    {
        // Find the document by its ID
        $document = Document::find($document_id);

        // Check if the document exists and is currently pending
        if ($document && $document->document_status == 'Pending') {
            // Update the document status to 'approved'
            $document->document_status = 'Approved';
            $document->save();

            // Flash a success message to the session
            session()->flash('success', 'Document approved successfully.');

            // Redirect to the approved documents page
            return redirect()->route('admin.documents.approved_docs');
        } else {
            // Flash an error message if the document cannot be approved
            session()->flash('error', 'Unable to approve document.');
            return back();
        }
    }

    public function decline($documentId, Request $request)
    {
        // Validate the remark
        $request->validate([
            'remark' => 'required|string|max:255',
        ]);

        // Find the document
        $document = Document::findOrFail($documentId);

        // Update the document status and remark
        $document->document_status = 'Declined';
        $document->remark = $request->remark;
        $document->save();

        return redirect()->route('admin.documents.declined_docs')->with('status', 'Document is declined.');
    }

    public function showApprovedDocuments()
    {
        // Fetch all approved documents
        $documents = Document::where('document_status', 'Approved')->get();
        return view('admin.documents.approved_docs', compact('documents'));
    }
    public function show($document_id)
    {
        $document = Document::findOrFail($document_id);

        if (!$document) {
            abort(404, 'Document not found.');
        }
    
        return view('admin.documents.view_docs', compact('document'));
    }
    public function searchDocuments(Request $request)
    {
        $query = $request->get('query', '');

        // Query for approved documents matching the query in document_name or related tags
        $documents = Document::where('document_status', 'Approved')
            ->where(function ($q) use ($query) {
                $q->where('document_name', 'LIKE', "%{$query}%")
                    ->orWhereHas('tags', function ($q2) use ($query) {
                        $q2->where('tag_name', 'LIKE', "%{$query}%");
                    });
            })
            ->get();

        // Return results as JSON
        return response()->json($documents);
    }

    public function searchDocumentsdean(Request $request)
    {
        $query = $request->get('query', '');

        // Query for approved documents matching the query in document_name or related tags
        $documents = Document::where('document_status', 'Approved')
            ->where('category_name', 'Memorandum') // Add the new condition here
            ->where(function ($q) use ($query) {
                $q->where('document_name', 'LIKE', "%{$query}%")
                    ->orWhereHas('tags', function ($q2) use ($query) {
                        $q2->where('tag_name', 'LIKE', "%{$query}%");
                    });
            })
            ->get();

        // Return results as JSON
        return response()->json($documents);
    }


    public function getPendingDocumentsCount()
    {
        // Fetch the count of pending documents
        $pendingCount = Document::where('document_status', 'Pending')->count();

        // Return the count
        return $pendingCount;
    }

    public function showAdminPage()
    {
        // Get the pending document count
        $pendingCount = $this->getPendingDocumentsCount();

        // Pass the count to the view
        return view('admin.admin_dashboard', ['pendingCount' => $pendingCount]);
    }


    public function serve($filename)
    {
        $file = storage_path('app/documents/' . $filename);

        if (file_exists($file)) {
            return response()->download($file);
        }

        return abort(404);
    }

    // Forwarded Documents
    public function forwardDocument(Request $request)
    {
        // Validate the request data
        $request->validate([
            'document_id' => 'required|exists:documents,document_id',
            'employee_id' => 'required|exists:employee,id',  // Ensure employee_id is an existing employee id
            'message' => 'nullable|string',
        ]);

        // Get the authenticated user
        $authenticatedUser = Auth::user();

        // Check if the user is authenticated and retrieve their employee_id
        $forwardedByEmployeeId = $authenticatedUser ? $authenticatedUser->employee_id : null;

        // Look up the employee's `id` (primary key) using the `employee_id`
        $forwardedBy = DB::table('employee')
            ->where('employee_id', $forwardedByEmployeeId)
            ->value('id');  // Retrieve the `id` (primary key) corresponding to the employee_id

        // Save the forwarded document details
        DB::table('forwarded_documents')->insert([
            'document_id' => $request->input('document_id'),
            'forwarded_by' => $forwardedBy, // Now this saves the correct `id`
            'forwarded_to' => $request->input('employee_id'), // Assuming forwarded_to references the `id`
            'forwarded_date' => now(),
            'status' => 'delivered',  // Default status
            'message' => $request->input('message'),
        ]);

        return response()->json(['message' => 'Document forwarded successfully.'], 200);
    }

    public function getDocumentDetails($id)
    {
        Log::info('Fetching forwarded document with ID: ' . $id);

        // Fetch the forwarded document with the given ID, and load the related document and sender
        $forwardedDocument = ForwardedDocument::with(['document', 'forwardedByEmployee'])
            ->find($id);

        if ($forwardedDocument) {
            // Return forwarded document details as JSON
            return response()->json([
                'subject-text' => $forwardedDocument->document->document_name ?? 'No Title',
                'snippet' => $forwardedDocument->message ?? 'No message available',
                'sender' => optional($forwardedDocument->forwardedByEmployee)->first_name . ' ' . optional($forwardedDocument->forwardedByEmployee)->last_name
            ]);
        } else {
            return response()->json(['error' => 'Forwarded document not found'], 404);
        }
    }

    public function updateStatus(Request $request, $forwardedDocumentId)
    {
        Log::info("Attempting to update status for document ID: " . $forwardedDocumentId);

        // Find the forwarded document by 'forwarded_document_id'
        $forwardedDocument = ForwardedDocument::where('forwarded_document_id', $forwardedDocumentId)->first();

        if (!$forwardedDocument) {
            Log::error("Document not found for ID: " . $forwardedDocumentId);
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        // Update the status to 'seen'
        $forwardedDocument->status = 'seen';
        $forwardedDocument->save();

        Log::info("Document status updated to 'seen' for ID: " . $forwardedDocumentId);

        return response()->json(['success' => true, 'message' => 'Document status updated to "seen".']);
    }

    public function viewRequest(){
        return view('admin.documents.requested_docs');
    }
}
