<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\View\View;

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

        // If a password is provided, hash it before storing.
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // Update the user's profile information.
        $user->update($validatedData);

        // Save the changes to the database.
        $user->save();

        // Redirect the user back to their profile page.
        return redirect()->route('user.show', ['id' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
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
}
