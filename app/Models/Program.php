<?php

namespace App\Models;

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'faculty_id',
        'degree_level',
        'title_en',
        'title_ar',
        'slug',
        'description_en',
        'description_ar',
        'tuition_min',
        'tuition_max',
        'currency',
        'min_admission_rate',
        'duration_years',
        'language',
        'highlights',
        'is_featured',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'degree_level' => DegreeLevel::class,
            'language' => ProgramLanguage::class,
            'highlights' => 'array',
            'tuition_min' => 'decimal:2',
            'tuition_max' => 'decimal:2',
            'min_admission_rate' => 'decimal:2',
            'duration_years' => 'decimal:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Faculty, $this>
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * @return BelongsToMany<University, $this>
     */
    public function universities(): BelongsToMany
    {
        return $this->belongsToMany(University::class, 'program_university')
            ->withPivot(['tuition_min', 'tuition_max'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<Application, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * @param  Builder<Program>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @param  Builder<Program>  $query
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * @param  Builder<Program>  $query
     */
    public function scopeDegree(Builder $query, string $level): void
    {
        $query->where('degree_level', $level);
    }
}
