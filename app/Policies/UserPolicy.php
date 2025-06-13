<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models (Admin only).
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     * Users can view their own profile, admins can view any profile.
     */
    public function view(User $user, User $model): bool
    {
        return $user->is_admin || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models (Admin only).
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     * Users can update their own profile, admins can update any profile.
     */
    public function update(User $user, User $model): bool
    {
        return $user->is_admin || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Users can delete their own account, admins can delete any account (except their own through admin panel).
     */
    public function delete(User $user, User $model): bool
    {
        // Users can delete their own account
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can delete other users' accounts (but not their own through admin panel)
        return $user->is_admin && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model (Admin only).
     */
    public function restore(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model (Admin only).
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can manage admin privileges (Admin only, but not for themselves).
     */
    public function manageAdminStatus(User $user, User $model): bool
    {
        return $user->is_admin && $user->id !== $model->id;
    }
}
