<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    /**
     * Show the login form
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            // Check if the user exists
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()
                    ->withErrors(['email' => 'This email address is not registered.'])
                    ->onlyInput('email');
            }

            // Attempt authentication
            if (!Auth::attempt($request->only('email', 'password'))) {
                return back()
                    ->withErrors(['password' => 'The provided password is incorrect.'])
                    ->onlyInput('email');
            }

            // Authentication successful
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login successful! You are now logged in.');

        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            // Return with error message
            return back()->withInput()
                ->with('error', 'An unexpected error occurred during login. Please try again.');
        }
    }

    /**
     * Handle user logout
     */
    public function destroy(Request $request)
    {
        // Log out the current authenticated user
        Auth::logout();

        // Invalidate the current session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home page with success message
        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
