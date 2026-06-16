<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case DocumentsRequired = 'documents_required';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Enrolled = 'enrolled';
    case Cancelled = 'cancelled';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending review',
            self::Reviewing => 'Under review',
            self::DocumentsRequired => 'Documents required',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Enrolled => 'Enrolled',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Statuses an admin/agent is allowed to transition an application to.
     *
     * @return array<int, string>
     */
    public static function manageable(): array
    {
        return self::values();
    }
}
