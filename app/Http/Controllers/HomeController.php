<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user) {
            // Fetch the employee record based on the user's employee_id
            $employee = Employee::where('employee_id', $user->employee_id)->first();
            $documents = Document::where('document_status', 'Approved')->whereNotIn('status',['archive','delete'])->get();

            if ($employee) {
                $position = $employee->position;
                
                if ($position == 'Admin') {
                    return view('home.admin',compact('documents'));
                } elseif ($position == 'Office_staff') {
                    return view('home.office_staff',compact('documents'));
                } elseif ($position == 'Dean') {
                    return view('home.dean',compact('documents'));
                }
            }
        }

        // Handle cases where the employee record doesn't exist or user is not authenticated
        return redirect()->route('login'); // Or any default view
    }

    public function adminHome()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }  // Fetch the documents from the database
        $documents = Document::where('document_status', 'Approved')->whereNotIn('status',['archive','delete'])->get();

        return view('home.admin', compact('documents','user'));
    }
}
