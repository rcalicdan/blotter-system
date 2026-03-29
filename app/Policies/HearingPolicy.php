<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Hearing;
use App\Models\User;

class HearingPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?Hearing $hearing = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Hearing $hearing): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, Hearing $hearing): bool
    {
        return $authUser->role->canModify();
    }
}