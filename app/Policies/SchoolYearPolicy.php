<?php

namespace App\Policies;

use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == 'admin';
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
        return $user->role == 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->role == 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->role == 'admin';
    }

}
