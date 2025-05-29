<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnrollmentPolicy
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
    // public function view(User $user, Enrollment $enrollment): bool
    // {
    //     return $user->role == 'admin';
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'faculty';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->role == 'faculty';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
       return $user->role == 'faculty';
    }

}
