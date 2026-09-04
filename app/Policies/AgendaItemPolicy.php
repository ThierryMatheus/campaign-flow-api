<?php

namespace App\Policies;

use App\Models\AgendaItem;
use App\Models\User;

class AgendaItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AgendaItem $agendaItem): bool
    {
        return $this->belongsToUserWorkspace($user, $agendaItem);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, AgendaItem $agendaItem): bool
    {
        return $this->belongsToUserWorkspace($user, $agendaItem);
    }

    public function delete(User $user, AgendaItem $agendaItem): bool
    {
        return $this->belongsToUserWorkspace($user, $agendaItem);
    }

    private function belongsToUserWorkspace(User $user, AgendaItem $agendaItem): bool
    {
        return $user->workspaces()
            ->where('workspaces.id', $agendaItem->workspace_id)
            ->exists();
    }
}
