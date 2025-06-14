<?php

namespace App\Policies;

use App\Models\InsuranceType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InsuranceTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['acct-staff','acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InsuranceType $insuranceType): bool
    {
        return $user->hasRole(['acct-staff','acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['acct-staff','acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InsuranceType $insuranceType): bool
    {
        return $user->hasRole(['acct-staff','acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InsuranceType $insuranceType): bool
    {
        return $user->hasRole(['acct-staff','acct-manager', 'cfo']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InsuranceType $insuranceType): bool
    {
        return $user->hasRole(['']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InsuranceType $insuranceType): bool
    {
        return $user->hasRole(['']);
    }
}
