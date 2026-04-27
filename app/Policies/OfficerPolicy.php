<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Officer;
use App\Models\User;

class OfficerPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?Officer $officer = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Officer $officer): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, Officer $officer): bool
    {
        return $authUser->role->canModify();
    }
}