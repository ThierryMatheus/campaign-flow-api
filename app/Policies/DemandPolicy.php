<?php

namespace App\Policies;

use App\Models\Demand;
use App\Models\User;

class DemandPolicy
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
    public function view(User $user, Demand $demand): bool
    {
        return $this->belongsToUserWorkspace($user, $demand);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Demand $demand): bool
    {
        return $this->belongsToUserWorkspace($user, $demand);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Demand $demand): bool
    {
        return $this->belongsToUserWorkspace($user, $demand);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Demand $demand): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Demand $demand): bool
    {
        return false;
    }

    public function belongsToUserWorkspace(User $user, Demand $demand)
    {
        return $user->workspaces()->where('workspaces.id', $demand->workspace_id)->exists();
    }
}
