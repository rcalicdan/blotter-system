<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Judge;
use App\Models\User;

class JudgePolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?Judge $judge = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Judge $judge): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, Judge $judge): bool
    {
        return $authUser->role->canModify();
    }
}