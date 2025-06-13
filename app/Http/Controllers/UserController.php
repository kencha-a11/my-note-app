<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', only: ['create', 'store']),
            new Middleware('auth', only: ['show', 'edit', 'update', 'destroy']),
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
            ]);

            Auth::login($user);

            // Debug: Check if user is actually logged in
            if (!Auth::check()) {
                Log::error('User not authenticated after Auth::login()', [
                    'user_id' => $user->id,
                    'session_id' => session()->getId()
                ]);
                return redirect()->route('login')->with('error', 'Authentication failed during registration');
            }

            Log::info('User registered and logged in successfully', [
                'user_id' => $user->id,
                'email' => $request->email,
                'auth_check' => Auth::check()
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! You are now logged in.');

        } catch (Exception $e) {
            Log::error('Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput($request->except('password'))
                ->with('error', 'Registration failed. Please try again later.');
        }
    }

    /**
     * Display the user's own profile
     */
    public function show()
    {
        try {
            $user = Auth::user();

            return view('pages.users.show', compact('user'));
        } catch (Exception $e) {
            Log::error('Failed to show user profile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load profile. Please try again.');
        }
    }

    /**
     * Show the form for editing the user's own profile
     */
    public function edit()
    {
        try {
            $user = Auth::user();

            return view('pages.users.edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Failed to show edit form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load edit form. Please try again.');
        }
    }

    /**
     * Update the user's own profile
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Prepare update data
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            Log::info('User profile updated', [
                'user_id' => $user->id,
            ]);

            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully!');

        } catch (Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput($request->except('password'))
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Delete the user's own account
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();
            $userEmail = $user->email;

            // Optional: Require password confirmation for account deletion
            if ($request->has('password')) {
                if (!Hash::check($request->password, $user->password)) {
                    return back()->with('error', 'Password confirmation failed.');
                }
            }

            $user->delete();
            Auth::logout();

            Log::info('User account deleted', [
                'deleted_user_email' => $userEmail,
            ]);

            return redirect()->route('home')
                ->with('success', 'Your account has been deleted successfully.');

        } catch (Exception $e) {
            Log::error('Account deletion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete account. Please try again.');
        }
    }
}
