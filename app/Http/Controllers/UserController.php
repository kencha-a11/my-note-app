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
}

