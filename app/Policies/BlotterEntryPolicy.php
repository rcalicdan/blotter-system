<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BlotterEntry;
use App\Models\User;

class BlotterEntryPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?BlotterEntry $blotterEntry = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, BlotterEntry $blotterEntry): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, BlotterEntry $blotterEntry): bool
    {
        return $authUser->role->canModify();
    }
}