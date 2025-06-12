<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function register(){
        return view('auth.register');
    }

    public function loginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($request->only('email', 'password'))) {
            // Authentication passed, regenerate session and redirect to intended page
            $request->session()->regenerate(); // Important for security
            return redirect()->intended('/home');
        }

        // Authentication failed, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // Keep the email input in the form
    }
    public function registerForm(Request $request)
    {
        // Handle registration logic here
        // Validate the request, create a new user, etc.
    }

    public function logout()
    {
        // Handle logout logic here
        // For example, you might use Auth::logout();
        return redirect()->route('home');
    }
}
