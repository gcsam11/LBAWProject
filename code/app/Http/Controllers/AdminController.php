<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('pages.admin_dashboard', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:user',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        // Save the user to the database
        $user->save();

        return redirect()->route('admin_dashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Admin $admin)
    {
        // Validate the request data.
        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'birthday' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'gender' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
        ]);

        // If a password is provided, hash it before storing.
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // Update the user's profile information.
        $user->update($validatedData);

        // Save the changes to the database.
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id, Admin $admin)
    {
        // Find the user by ID.
        $user = User::findOrFail($id);

        // Delete the user.
        $user->delete();

        return redirect()->route('admin_dashboard');
    }
}
        
