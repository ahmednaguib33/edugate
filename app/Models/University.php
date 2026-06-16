<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use Database\Factories\UniversityFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class University extends Model
{
    /** @use HasFactory<UniversityFactory> */
    use HasFactory, HasLocalizedContent;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'city',
        'description_en',
        'description_ar',
        'logo_url',
        'website',
        'is_accredited',
        'established_year',
        'global_ranking',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_accredited' => 'boolean',
            'is_active' => 'boolean',
            'established_year' => 'integer',
            'global_ranking' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsToMany<Program, $this>
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_university')
            ->withPivot(['tuition_min', 'tuition_max'])
            ->withTimestamps();
    }

    /**
     * @param  Builder<University>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
