<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    // This might be in DashboardController or wherever your dashboard route points
    public function index()
    {
        // Remove or fix this check
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access this area.');
        }

        return view('dashboard');
    }
}
