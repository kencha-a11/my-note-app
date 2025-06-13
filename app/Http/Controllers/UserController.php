<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public static function middleware(): array
    {
        return [
            'guest' => ['create', 'store'],
            'auth' => ['index', 'show', 'edit', 'update', 'destroy'],
        ];
    }
    /**
     * Show the registration form
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Log in the user after registration
            Auth::login($user);
            Log::info('User registered and logged in', ['email' => $request->email]);

            // Redirect to the dashboard with success message
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Registration successful! You are now logged in.');

        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            // Return with error message
            return back()->withInput()
                ->with('error', 'Registration failed. Please try again later.');
        }
    }

    /**
     * Display the user profile
     */
    public function show(User $user)
    {
        // Ensure the authenticated user can only view their own profile
        if (Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.users.show', compact('user'));
    }
    /**
     * Show the form for editing the user profile
     */
    public function edit(User $user)
    {
        // Ensure the authenticated user can only edit their own profile
        if (Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.users.edit', compact('user'));
    }
    /**
     * Update the user profile
     */
    public function update(Request $request, User $user)
    {
        // Ensure the authenticated user can only update their own profile
        if (Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        try {
            // Update the user details
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            Log::info('User profile updated', ['email' => $user->email]);

            return redirect()->route('user.show', Auth::user()->id)
                ->with('success', 'Profile updated successfully!');

        } catch (Exception $e) {
            Log::error('Profile update failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }
    /**
     * Delete the user account
     */
    public function destroy(User $user)
    {
        // Ensure the authenticated user can only delete their own account
        if (Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete the user account
            $user->delete();
            Auth::logout();

            Log::info('User account deleted', ['email' => $user->email]);

            return redirect()->route('home')
                ->with('success', 'Account deleted successfully.');

        } catch (Exception $e) {
            Log::error('Account deletion failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete account. Please try again.');
        }
    }
    /**
     * Display a listing of the users
     */
    public function index()
    {
        // Ensure the user is authenticated and has permission to view the user list
        if (!Auth::check() || !Auth::user()->can('viewAny', User::class)) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::all(); // Fetch all users

        return view('users.index', compact('users'));
    }
}

