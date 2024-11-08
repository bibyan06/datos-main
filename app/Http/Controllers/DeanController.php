<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\ForwardedDocument;
use App\Models\SendDocument;
use App\Models\Role; // Include the Role model
use Illuminate\Support\Facades\Log;

class DeanController extends Controller
{
    public function dashboard()
    {
        return view('dean.dean_dashboard');
    }
    public function dean_account()
    {
        return view('dean.dean_account');
    }

    public function upload_document()
    {
        return view('dean.dean_upload_document');
    }

    public function edit_docs()
    {
        return view('dean.documents.dean_edit_docs');
    }

    public function notification()
    {
        return view('dean.documents.dean_notification');
    }

    public function request()
    {
        $collegeID = auth()->user()->college;
        $collegeName = College::where('college_id', $collegeID)->first()->college_name;
        return view('dean.documents.dean_request', compact('collegeName'));
    }

    public function search()
    {
        return view('dean.documents.dean_search');
    }

    public function view_docs()
    {
        return view('dean.documents.dean_view_docs');
    }
    public function memorandum()
    {
        $documents = Document::where('category_name', 'Memorandum')
            ->where('document_status', 'Approved')
            ->get();

        return view('home.dean', compact('documents'));
    }

    public function admin_order()
    {
        return view('dean.documents.admin_order');
    }

    public function mrsp()
    {
        return view('dean.documents.mrsp');
    }

    public function cms()
    {
        return view('dean.documents.cms');
    }

    public function audited_dv()
    {
        return view('dean.documents.audited_dv');
    }

    public function someMethod()
    {
        $user = auth()->user();

        if (strpos($user->employee_id, '03') !== 0) {
            abort(403, 'Unauthorized action.');
        }

        // Proceed with the action
    }
    public function showHomePage()
    {
        $documents = Document::where('category_name', 'Memorandum')
            ->where('document_status', 'Approved')
            ->get();

        return view('home.dean', compact('documents'));
    }


    public function showApprovedDocuments()
    {
        // Fetch all approved documents
       $documents = Document::where('document_status', 'Approved')->where('status',NULL)->get();
        return view('dean.documents.dean_search', compact('documents'));
    }

    public function showDeanHome()
    {
        $documents = Document::where('category_name', 'Memorandum')
            ->where('document_status', 'Approved')
            ->get();

        return view('home.dean', compact('documents'));
    }


    // Function to handle the addition of a new dean account
    public function storeDeanAccount(Request $request)
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'college_id' => 'required|integer|exists:college,college_id', // Validate the college_id
                'password' => 'required|string|min:6',
                'employee_id' => 'required|string|unique:users',
            ]);

            // Fetch the role ID for the dean from the roles table
            $role = Role::where('position', 'Dean')->first();

            if (!$role) {
                // Handle the case where the 'dean' role doesn't exist
                \Log::error('Role not found for position: dean');
                return response()->json(['success' => false, 'message' => 'Dean role not found.']);
            }

            // Fetch the college name based on the provided college_id
            $college = DB::table('college')->where('college_id', $validated['college_id'])->first();

            if (!$college) {
                return response()->json(['success' => false, 'message' => 'College not found.']);
            }

            // Create a new dean account and assign the role
            $user = User::create([
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'email' => $validated['email'],
                'college' => $college->college_id,  // Save the college name in the users table
                'password' => bcrypt($validated['password']),
                'employee_id' => $validated['employee_id'],
                'role_id' => $role->id,  // Save the fetched role ID
            ]);

            // Send verification email
            $user->sendEmailVerificationNotification();

            // // Create an entry in the Employee table
            // $employee = Employee::create([
            //     'employee_id' => $validated['employee_id'], // Use the validated employee_id
            //     'last_name' => $user->last_name,
            //     'first_name' => $user->first_name,
            //     'position' => 'Dean', 
            // ]);

            // Create an entry in the Dean table
            DB::table('dean')->insert([
                'user_id' => $user->user_id,     // Reference to the user_id from the users table
                'college_id' => $validated['college_id'],  // Reference to the college_id from the form
                'role_id' => $role->id,          // Reference to the role_id from the users table
            ]);

            // Send success response
            return response()->json(['success' => true, 'message' => 'Dean account created successfully. Verification email sent.']);
        } catch (\Exception $e) {
            // Log the error and send failure response
            \Log::error('Failed to create dean account:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to create dean account.' . $e->getMessage()]);
        }
    }
    public function archiveDocs(){
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])->where('forwarded_to', $id)->where('status', 'archive')->get();
        
        $sent = SendDocument::with(['recipient', 'document', 'sender'])->where('issued_by', $id)->where('status', 'archive')->get();
        return view('dean.dean_archive', compact('forward'));
    }
    public function trash(){
        $id = Employee::where('employee_id', auth()->user()->employee_id)->first()->id;
        $forward = ForwardedDocument::with(['forwardedTo', 'documents', 'forwardedBy'])->where('forwarded_to', $id)->where('status', 'deleted')->get();
        $sent = SendDocument::with(['recipient', 'document', 'sender'])->where('issued_to', $id)->where('status', 'deleted')->get();
    
        return view('dean.dean_trash', compact('forward', 'sent'));
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
