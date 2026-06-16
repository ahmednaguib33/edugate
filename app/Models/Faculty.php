<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Database\Factories\FacultyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    /** @use HasFactory<FacultyFactory> */
    use HasFactory, HasLocalizedContent;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'description_en',
        'description_ar',
        'icon',
        'image_url',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * A decorative emoji used on faculty/program cards in the UI.
     */
    public function emoji(): string
    {
        return [
            'medicine' => '🩺',
            'dentistry' => '🦷',
            'engineering' => '⚙️',
            'petroleum-engineering' => '🛢️',
            'energy-engineering' => '⚡',
            'computers-information' => '💻',
            'science' => '🔬',
            'agriculture' => '🌱',
            'veterinary-medicine' => '🐾',
            'nursing' => '🩹',
            'physical-therapy' => '💪',
            'law' => '⚖️',
            'al-alsun' => '🗣️',
            'archaeology' => '🏛️',
            'mass-communication' => '📡',
            'business-administration' => '💼',
            'economics-political-science' => '📈',
            'education' => '🎓',
            'physical-education' => '🏅',
            'fine-arts' => '🎨',
            'applied-arts' => '✒️',
            'dar-al-ulum' => '📖',
        ][$this->slug] ?? '🎓';
    }

    /**
     * @return HasMany<Program, $this>
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * @param  Builder<Faculty>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
