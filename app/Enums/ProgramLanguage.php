<?php

namespace App\Enums;

enum ProgramLanguage: string
{
    case Arabic = 'arabic';
    case English = 'english';
    case Both = 'both';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $lang) => $lang->value, self::cases());
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::Arabic => 'Arabic',
            self::English => 'English',
            self::Both => 'Arabic & English',
        };
    }

    public function labelAr(): string
    {
        return match ($this) {
            self::Arabic => 'عربي',
            self::English => 'إنجليزي',
            self::Both => 'عربي وإنجليزي',
        };
    }
}
