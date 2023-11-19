<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function create(array $data)
    {
        // Create a new user instance
        $user = new User([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        // Save the user to the database
        $user->save();

        // You can perform additional actions here if needed

        // Redirect to a success page or return a response
        return redirect()->route('registration.success')
            ->with('success', 'User registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Get the user.
        $user = User::findOrFail($id);

        // Check if the current user can see (show) the user.
        $this->authorize('show', Auth::user());  

        // Use the pages.user template to display the user.
        return view('pages.user', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Check if the current user can update the profile.
            $this->authorize('update', $user);
    
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
    
            // Remove null values from the validated data.
            $filteredData = array_filter($validatedData, function ($value) {
                return $value !== null;
            });
    
            // If a password is provided, hash it before storing.
            if (isset($filteredData['password'])) {
                $filteredData['password'] = Hash::make($filteredData['password']);
            }
    
            // Update the user's profile information.
            $user->update($filteredData);
    
            // Save the changes to the database.
            $user->save();
    
            // Redirect the user back to their profile page.
            return redirect()->route('profile')->with('success', 'Info updated successfully');
        } catch (\Exception $e) {
            // Log the error message.
            \Log::error('Failed to update user with ID: ' . $user->id . '. Error: ' . $e->getMessage());
    
            // Redirect back with an error message.
            return redirect()->route('profile')->with('error', 'Failed to update info');
        }
    }    

    /**
     * Change user password.
     */
    public function change_password(Request $request)
    {
        $request->validate([
            'last_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Verify if the provided last password is correct
        if (!password_verify($request->input('last_password'), $user->password)) {
            throw ValidationException::withMessages([
                'last_password' => ['The provided last password is incorrect.'],
            ]);
        }

        // Update the user's password with the new one
        $user->update([
            'password' => $request->input('new_password'),
        ]);

        return redirect()->route('profile')->with('success', 'Password changed successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        // Check if the current user can delete the user.
        $this->authorize('delete', $user);

        // Delete the user.
        $user->delete();

        // Redirect the user to the user index page.
        return redirect()->route('user.index');
    }
}
