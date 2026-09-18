<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Auth\Access\Response;

class VoterPolicy
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
    public function view(User $user, Voter $voter): bool
    {
        return $this->belongsToUserWorkspace($user, $voter);
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
    public function update(User $user, Voter $voter): bool
    {
        return $this->belongsToUserWorkspace($user, $voter);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Voter $voter): bool
    {
        return $this->belongsToUserWorkspace($user, $voter);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Voter $voter): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Voter $voter): bool
    {
        return false;
    }

    private function belongsToUserWorkspace(User $user, Voter $voter): bool
    {
        return $user->workspaces()
            ->where('workspaces.id', $voter->workspace_id)
            ->exists();
    }
}
