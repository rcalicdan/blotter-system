<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Staff = 'staff';

    public function canView(): bool
    {
        return true;
    }

    public function canCreate(): bool
    {
        return match ($this) {
            self::SuperAdmin, self::Admin => true,
            default => false,
        };
    }

    public function canUpdate(self $targetRole): bool
    {
        return match ($this) {
            self::SuperAdmin => true,
            self::Admin      => $targetRole === self::Staff,
            default          => false,
        };
    }

    public function canDelete(self $targetRole): bool
    {
        return match ($this) {
            self::SuperAdmin => true,
            self::Admin      => $targetRole === self::Staff,
            default          => false,
        };
    }
}