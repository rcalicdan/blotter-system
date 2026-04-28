<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CriminalRecord;
use App\Models\User;

class CriminalRecordPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->role->canView();
    }

    public function view(User $authUser, ?CriminalRecord $record = null): bool
    {
        return $authUser->role->canView();
    }

    public function create(User $authUser): bool
    {
        return $authUser->role->canCreate();
    }

    public function update(User $authUser, CriminalRecord $record): bool
    {
        return $authUser->role->canModify();
    }

    public function delete(User $authUser, CriminalRecord $record): bool
    {
        return $authUser->role->canModify();
    }
}