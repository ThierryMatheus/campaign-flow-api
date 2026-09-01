<?php

namespace App\Policies;

use App\Models\FieldActivity;
use App\Models\User;

class FieldActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FieldActivity $fieldActivity): bool
    {
        return $this->belongsToUserWorkspace($user, $fieldActivity);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FieldActivity $fieldActivity): bool
    {
        return $this->belongsToUserWorkspace($user, $fieldActivity);
    }

    public function delete(User $user, FieldActivity $fieldActivity): bool
    {
        return $this->belongsToUserWorkspace($user, $fieldActivity);
    }

    private function belongsToUserWorkspace(User $user, FieldActivity $fieldActivity): bool
    {
        return $user->workspaces()
            ->where('workspaces.id', $fieldActivity->workspace_id)
            ->exists();
    }
}
