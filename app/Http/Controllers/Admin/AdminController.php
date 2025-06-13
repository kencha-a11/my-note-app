<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('admin'), // Custom admin middleware
        ];
    }

    /**
     * Display a listing of all users (Admin only)
     */
    public function index()
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('viewAny', User::class)) {
                abort(403);
            }

            $users = User::paginate(15);

            return view('admin.users.index', compact('users'));
        } catch (Exception $e) {
            Log::error('Admin failed to load users', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load users. Please try again.');
        }
    }

    /**
     * Show the form for creating a new user (Admin only)
     */
    public function create()
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('create', User::class)) {
                abort(403);
            }

            return view('admin.users.create');
        } catch (Exception $e) {
            Log::error('Admin failed to show create form', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load create form. Please try again.');
        }
    }

    /**
     * Store a newly created user (Admin only)
     */
    public function store(Request $request)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('create', User::class)) {
                abort(403);
            }

            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'is_admin' => 'boolean',
            ]);

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->boolean('is_admin', false),
            ]);

            Log::info('Admin created new user', [
                'admin_id' => Auth::id(),
                'created_user_id' => $user->id,
                'created_user_email' => $user->email,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');

        } catch (Exception $e) {
            Log::error('Admin user creation failed', [
                'admin_id' => Auth::id(),
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput($request->except('password'))
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Display the specified user (Admin only)
     */
    public function show(User $user)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('view', $user)) {
                abort(403);
            }

            return view('admin.users.show', compact('user'));
        } catch (Exception $e) {
            Log::error('Admin failed to show user profile', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load user profile. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified user (Admin only)
     */
    public function edit(User $user)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('update', $user)) {
                abort(403);
            }

            return view('admin.users.edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Admin failed to show edit form', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load edit form. Please try again.');
        }
    }

    /**
     * Update the specified user (Admin only)
     */
    public function update(Request $request, User $user)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('update', $user)) {
                abort(403);
            }

            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8|confirmed',
                'is_admin' => 'boolean',
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

            // Admin can update admin status
            if ($request->has('is_admin')) {
                $updateData['is_admin'] = $request->boolean('is_admin');
            }

            $user->update($updateData);

            Log::info('Admin updated user profile', [
                'admin_id' => Auth::id(),
                'updated_user_id' => $user->id,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (Exception $e) {
            Log::error('Admin user update failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput($request->except('password'))
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified user (Admin only)
     */
    public function destroy(User $user)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('delete', $user)) {
                abort(403);
            }

            // Prevent admin from deleting their own account through admin panel
            if (Auth::id() === $user->id) {
                return back()->with('error', 'You cannot delete your own account through the admin panel. Use your profile settings instead.');
            }

            $userEmail = $user->email;
            $user->delete();

            Log::info('Admin deleted user account', [
                'admin_id' => Auth::id(),
                'deleted_user_email' => $userEmail,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User account deleted successfully.');

        } catch (Exception $e) {
            Log::error('Admin account deletion failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete user account. Please try again.');
        }
    }

    /**
     * Toggle user admin status (Admin only)
     */
    public function toggleAdmin(User $user)
    {
        try {
            // Check admin authorization
            if (Auth::user()->cannot('update', $user)) {
                abort(403);
            }

            // Prevent admin from removing their own admin status
            if (Auth::id() === $user->id) {
                return back()->with('error', 'You cannot modify your own admin status.');
            }

            $user->update([
                'is_admin' => !$user->is_admin
            ]);

            $status = $user->is_admin ? 'granted' : 'revoked';

            Log::info("Admin {$status} admin privileges", [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'new_admin_status' => $user->is_admin,
            ]);

            return back()->with('success', "Admin privileges {$status} successfully.");

        } catch (Exception $e) {
            Log::error('Admin toggle failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update admin status. Please try again.');
        }
    }
}
