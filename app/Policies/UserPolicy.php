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
     * This policy needs to distinguish between:
     * 1. A user (admin or regular) editing THEIR OWN profile (always allowed).
     * 2. An admin editing ANOTHER user's profile (where restrictions apply).
     */
    public function update(User $user, User $model): Response
    {
        // Scenario 1: A user (admin or regular) is trying to update THEIR OWN profile.
        // This should always be allowed, regardless of admin status, for personal profile management.
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // Scenario 2: An admin is trying to update ANOTHER user's profile.
        // First, check if the current user is an admin. If not, they can't update others.
        if (!$user->is_admin) {
            return Response::deny('You do not have permission to update other users.');
        }

        // Now, since the current user IS an admin and is NOT updating their own profile:
        // Rule: An admin cannot update another admin's account.
        if ($model->is_admin) {
            return Response::deny('You cannot update another administrator\'s account.');
        }

        // If the current user is an admin, they are not updating themselves,
        // and the target user is NOT an admin, then allow the update.
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     * This policy needs to distinguish between:
     * 1. A user (admin or regular) deleting THEIR OWN account (always allowed).
     * 2. An admin deleting ANOTHER user's account (where restrictions apply).
     */
    public function delete(User $user, User $model): Response
    {
        // Scenario 1: A user (admin or regular) is trying to delete THEIR OWN account.
        // This should always be allowed for personal account management.
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // Scenario 2: An admin is trying to delete ANOTHER user's account.
        // First, check if the current user is an admin. If not, they can't delete others.
        if (!$user->is_admin) {
            return Response::deny('You do not have permission to delete other users.');
        }

        // Now, since the current user IS an admin and is NOT deleting their own account:
        // Rule: An admin cannot delete another admin's account.
        if ($model->is_admin) {
            return Response::deny('You cannot delete another administrator\'s account.');
        }

        // If the current user is an admin, they are not deleting themselves,
        // and the target user is NOT an admin, then allow the deletion.
        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model (Admin only, not self, not other admins).
     */
    public function restore(User $user, User $model): Response
    {
        // Only admins can restore
        if (!$user->is_admin) {
            return Response::deny('You do not have permission to restore users.');
        }

        // Admins cannot restore themselves or other admins
        if ($user->id === $model->id || $model->is_admin) {
            return Response::deny('You cannot restore this account as it is an administrator\'s or your own.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can permanently delete the model (Admin only, not self, not other admins).
     */
    public function forceDelete(User $user, User $model): Response
    {
        // Only admins can force delete
        if (!$user->is_admin) {
            return Response::deny('You do not have permission to permanently delete users.');
        }

        // Admins cannot force delete themselves or other admins
        if ($user->id === $model->id || $model->is_admin) {
            return Response::deny('You cannot permanently delete this account as it is an administrator\'s or your own.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can manage admin privileges (Admin only, not for themselves, not for other admins).
     * This is specific to changing the 'is_admin' status.
     */
    public function toggleAdmin(User $user, User $model): Response
    {
        // Only an admin can manage admin statuses
        if (!$user->is_admin) {
            return Response::deny('You do not have permission to manage admin statuses.');
        }

        // An admin cannot change their own admin status
        if ($user->id === $model->id) {
            return Response::deny('You cannot modify your own admin status.');
        }

        // An admin cannot change another admin's status
        if ($model->is_admin) {
            return Response::deny('You cannot change the admin status of another administrator.');
        }

        // If all checks pass, allow the admin to toggle the status of a regular user
        return Response::allow();
    }
}
