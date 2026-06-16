<?php

namespace App\Models\Concerns;

trait HasLocalizedContent
{
    /**
     * Return a bilingual field in the current locale, falling back to English.
     *
     * Example: $program->loc('title') reads title_ar or title_en.
     */
    public function loc(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();

        return $this->{"{$field}_{$locale}"} ?? $this->{"{$field}_en"} ?? null;
    }
}
