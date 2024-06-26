<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Subject $Subject): bool
    {

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return  $user->status === "Manager" || $user->status === "Teacher";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subject $Subject): bool
    {
        return  $user->status === "Teacher" || $user->status === "Manager";
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subject $Subject): bool
    {
        return  $user->status === "Manager";
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Subject $Subject): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Subject $Subject): bool
    {
        return false;
    }
}
