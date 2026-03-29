<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Dispute;
use App\Models\User;

class DisputePolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?Dispute $dispute = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Dispute $dispute): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, Dispute $dispute): bool
    {
        return $authUser->role->canModify();
    }
}