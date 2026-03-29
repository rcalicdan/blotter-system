<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?Person $person = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, Person $person): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, Person $person): bool
    {
        return $authUser->role->canModify();
    }
}
