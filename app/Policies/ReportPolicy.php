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
        return $user->hasRole(['acct-staff', 'cashier', 'acct-manager', 'agent', 'cfo']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->hasRole(['acct-staff', 'cashier', 'acct-manager', 'agent', 'cfo']);
    }

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
        return $user->hasRole(['cashier', 'acct-staff',]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole(['acct-staff', 'acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        return $user->hasRole(['acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Report $report): bool
    // {
    //     //
    // }
}
