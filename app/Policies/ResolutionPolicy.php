<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Resolution;
use App\Models\User;

class ResolutionPolicy
{
    public function view(User $authUser, ?Resolution $resolution = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Resolution $resolution): bool
    {
        return $authUser->role->canModify();
    }
}