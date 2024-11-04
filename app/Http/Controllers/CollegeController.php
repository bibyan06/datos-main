<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College; 
use App\Models\User;

class CollegeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'college_name' => 'required|string|max:255',
        ]);

        try {
            // Create a new College instance and save the college_name
            $college = new College();
            $college->college_name = $request->college_name;
            $college->save();

            return response()->json(['success' => true, 'message' => 'College added successfully!']);
        } catch (\Exception $e) {
            // Return error message if saving fails
            return response()->json(['success' => false, 'message' => 'Failed to add college: ' . $e->getMessage()]);
        }
    }

    public function showCollegeDeanForm()
    {
       // Retrieve users or deans data
       $users = User::with('colleges')->where('role_id', '3')->get(); 

       // Retrieve the list of colleges
       $colleges = College::all();
           
       // Pass both variables to the view
       return view('admin.college_dean', compact('users', 'colleges'));
    }

    
}
