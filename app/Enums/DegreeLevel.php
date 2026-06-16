<?php

namespace App\Enums;

enum DegreeLevel: string
{
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Phd = 'phd';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $level) => $level->value, self::cases());
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::Bachelor => 'Bachelor',
            self::Master => 'Master',
            self::Phd => 'PhD',
        };
    }

    public function labelAr(): string
    {
        return match ($this) {
            self::Bachelor => 'بكالوريوس',
            self::Master => 'ماجستير',
            self::Phd => 'دكتوراه',
        };
    }

    public function label(): string
    {
        return app()->getLocale() === 'ar' ? $this->labelAr() : $this->labelEn();
    }
}
