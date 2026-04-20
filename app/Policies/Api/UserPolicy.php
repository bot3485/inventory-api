<?php

namespace App\Policies\Api;

use App\Models\Api\User;

/**
 * UserPolicy
 * Handles authorization logic for User model actions.
 */
class UserPolicy
{
    /**
     * Determine if the user can view the list of all employees.
     */
    public function viewAny(User $user): bool
    {
        // Allow access for Admins and Managers.
        // Regular users are restricted from viewing the full directory.
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine if the user can view a specific employee's profile.
     */
    public function view(User $user, User $model): bool
    {
        // Admin, Manager, or the owner of the profile can view it.
        return $user->role === 'admin'
            || $user->role === 'manager'
            || $user->id === $model->id;
    }

    /**
     * Determine if the user can register/create new employees.
     */
    public function create(User $user): bool
    {
        // Only Admin and Manager roles can register new accounts.
        return $user->role === 'admin' || $user->role === 'manager';
    }

    /**
     * Determine if the user can update the employee's data.
     */
    public function update(?User $user, User $model): bool
    {
        if (!$user) {
            return false;
        }

        // Admins have full access to update any profile.
        if ($user->role === 'admin') return true;

        // Managers can only edit accounts with the 'user' role
        // (they cannot edit other managers or admins).
        if ($user->role === 'manager' && $model->role === 'user') return true;

        // Users are only allowed to update their own profile data.
        return $user->id === $model->id;
    }

    /**
     * Determine if the user can remove employees from the system.
     */
    public function delete(User $user, User $model): bool
    {
        // Termination/Deletion is restricted to the Admin role only.
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can permanently delete an employee.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Fixed: replaced legacy is_admin check with modern role check.
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can restore a deleted employee.
     */
    public function restore(User $user, User $model): bool
    {
        // Restoration is restricted to Admins.
        return $user->role === 'admin';
    }
}
