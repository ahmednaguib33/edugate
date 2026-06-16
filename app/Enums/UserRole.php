<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Agent = 'agent';
    case Student = 'student';

    /**
     * Roles that are considered staff / back-office users.
     *
     * @return array<int, string>
     */
    public static function staff(): array
    {
        return [self::Admin->value, self::Agent->value];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $role) => $role->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Agent => 'Sales Agent',
            self::Student => 'Student',
        };
    }
}
