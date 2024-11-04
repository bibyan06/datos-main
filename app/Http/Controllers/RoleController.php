<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    // Show form to assign roles
    public function index()
    {
        // Get all users for role assignment
        $users = User::all();
        return view('roles.index', compact('users'));
    }

    // Assign role to user
    public function assign(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'role' => 'required|string',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Assign the role
        $user->role = $request->input('role');
        $user->save();

        // Redirect back with success message
        return redirect()->route('roles.index')->with('success', 'Role assigned successfully.');
    }

    // Remove role from user
    public function remove($id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Remove the role
        $user->role = null;
        $user->save();

        // Redirect back with success message
        return redirect()->route('roles.index')->with('success', 'Role removed successfully.');
    }
}
