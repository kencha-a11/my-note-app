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
            // Check admin authorization using policy
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
            // Check admin authorization using policy
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
            // Check admin authorization using policy
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
            // Check admin authorization using policy
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
            // Check admin authorization using policy. This will now handle self-edit and other admin edits.
            if (Auth::user()->cannot('update', $user)) {
                // If denied by policy, Laravel will automatically redirect with the policy's message
                // but for an 'edit' view, we might want a friendlier message or a redirect
                // instead of an abort(403) which is often a blank page.
                // However, since your policy returns Response::deny, abort(403) will be caught by global exception handler
                // and you might have a custom error page for 403 or it will just show Laravel's default one.
                // For a more graceful redirect with message, you could explicitly check here:
                return redirect()->route('admin.users.show', $user)->with('error', 'You are not authorized to edit this user.');
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
            // Check admin authorization using policy. This is the critical server-side check.
            // If the policy denies, it will throw an AuthorizationException, which Laravel's
            // default handler will convert to a 403 response.
            if (Auth::user()->cannot('update', $user)) {
                // You can optionally return a more specific message if you don't rely solely on the policy's Response::deny message
                return back()->with('error', 'You are not authorized to perform this update.');
            }

            // Validate the request data (after authorization check)
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8|confirmed',
                // is_admin should be present, but its modification will be controlled by policy
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

            // The 'is_admin' field is automatically handled by the policy if you attempt to change it
            // for an unauthorized user (like another admin). The policy takes precedence.
            // So, you can safely update it here; the policy would have blocked it earlier if needed.
            $updateData['is_admin'] = $request->boolean('is_admin');


            $user->update($updateData);

            Log::info('Admin updated user profile', [
                'admin_id' => Auth::id(),
                'updated_user_id' => $user->id,
                'updated_user_email' => $user->email, // Added for better logging
                'is_admin_set_to' => $updateData['is_admin'] ?? $user->is_admin // Log final status
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (Exception $e) {
            Log::error('Admin user update failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(), // Include trace for debugging
            ]);

            // If an AuthorizationException is thrown by the policy, a 403 error is typically displayed.
            // If you want to catch that specific exception and show a flash message instead, you'd need:
            // catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            //     return back()->with('error', $e->getMessage());
            // }
            // For general exceptions, the current catch block is fine.
            return back()->withInput($request->except('password'))
                ->with('error', 'Failed to update user. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user (Admin only)
     */
    public function destroy(User $user)
    {
        try {
            // Check admin authorization using policy. This is the critical server-side check.
            if (Auth::user()->cannot('delete', $user)) {
                return back()->with('error', 'You are not authorized to delete this user.');
            }

            $userEmail = $user->email;
            $user->delete();

            Log::info('Admin deleted user account', [
                'admin_id' => Auth::id(),
                'deleted_user_id' => $user->id, // Log ID of deleted user
                'deleted_user_email' => $userEmail,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User account deleted successfully.');

        } catch (Exception $e) {
            Log::error('Admin account deletion failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to delete user account. ' . $e->getMessage());
        }
    }

    /**
     * Toggle user admin status (Admin only)
     */
    public function toggleAdmin(User $user)
    {
        try {
            // Check admin authorization using policy. This will handle restrictions.
            if (Auth::user()->cannot('toggleAdmin', $user)) {
                return back()->with('error', 'You are not authorized to change this user\'s admin status.');
            }

            $user->is_admin = !$user->is_admin;
            $user->save();

            $status = $user->is_admin ? 'granted' : 'revoked';

            Log::info("Admin privileges {$status}", [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'target_user_email' => $user->email, // Added for better logging
                'new_status' => $user->is_admin,
            ]);

            return back()->with('success', "Admin privileges {$status} successfully for {$user->name}.");

        } catch (Exception $e) {
            Log::error('Admin toggle failed', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to update admin status: ' . $e->getMessage());
        }
    }
}
