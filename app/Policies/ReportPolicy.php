<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['acct-staff', 'cashier', 'acct-manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Report $report): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['cashier']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->hasAnyRole(['cashier', 'acct-staff']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole(['super-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Report $report): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Report $report): bool
    // {
    //     //
    // }
}
